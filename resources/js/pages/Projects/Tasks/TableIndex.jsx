import { openConfirmModal } from "@/components/ConfirmModal";
import Layout from "@/layouts/MainLayout";
import { router, usePage } from "@inertiajs/react";
import { DatePickerInput } from "@mantine/dates";
import { CreateTaskDrawer } from "./Drawers/CreateTaskDrawer";
import { EditTaskDrawer } from "./Drawers/EditTaskDrawer";
import useTaskDrawerStore from "@/hooks/store/useTaskDrawerStore";
import useTasksStore from "@/hooks/store/useTasksStore";
import { IconPlus } from "@tabler/icons-react"; 
import {
  ActionIcon,
  Badge,
  Center,
  Grid,
  Group,
  LoadingOverlay,
  Menu,
  Pagination,
  Select,
  Table,
  Text,
  TextInput,
  Title,
  UnstyledButton,
  rem,
} from "@mantine/core";
import {
  IconArrowsSort,
  IconDots,
  IconPencil,
  IconSearch,
  IconSortAscending,
  IconSortDescending,
  IconTrash,
} from "@tabler/icons-react";
import axios from "axios";
import dayjs from "dayjs";
import { useEffect, useRef, useState } from "react";

const STATUS_COLORS = {
  diajukan: "grape",
  diproses: "blue",
  "tidak dilanjutakan": "gray",
  ditolak: "red",
  selesai: "green",
};

const DEFAULT_PARAMS = {
  search: "",
  sort: "created_at",
  direction: "desc",
  created_by_user_id: "",
  assigned_to_user_id: "",
  created_from: "",
  created_to: "",
  page: 1,
  per_page: 15,
};

function Th({ label, field, params, onSort }) {
  const active = params.sort === field;
  const Icon = active
    ? params.direction === "asc"
      ? IconSortAscending
      : IconSortDescending
    : IconArrowsSort;

  return (
    <Table.Th>
      <UnstyledButton onClick={() => onSort(field)} style={{ display: "flex", alignItems: "center", gap: 4 }}>
        <Text fw={600} fz="sm">
          {label}
        </Text>
        <Icon style={{ width: rem(14), height: rem(14) }} opacity={active ? 1 : 0.4} />
      </UnstyledButton>
    </Table.Th>
  );
}

export default function TableIndex() {
  const { project, usersWithAccessToProject, filters } = usePage().props;

  const [params, setParams] = useState({ ...DEFAULT_PARAMS, ...filters });
  const [searchInput, setSearchInput] = useState(filters.search || "");
  const [page, setPage] = useState(null); // { data, current_page, last_page, total, per_page }
  const [loading, setLoading] = useState(true);

  const requestId = useRef(0);

  const { edit, create, openCreateTask, openEditTask } = useTaskDrawerStore();
  const { addTask } = useTasksStore();

  const paramsRef = useRef(params);
  useEffect(() => { paramsRef.current = params; }, [params]);

  const prevEditOpened = useRef(edit.opened);
  useEffect(() => {
    if (prevEditOpened.current && !edit.opened) {
      fetchData(paramsRef.current); // drawer edit baru ditutup → refresh baris tabel
    }
    prevEditOpened.current = edit.opened;
  }, [edit.opened]);

  const prevCreateOpened = useRef(create.opened);
  useEffect(() => {
    if (prevCreateOpened.current && !create.opened) {
      fetchData(paramsRef.current); // drawer create baru ditutup → refresh baris tabel
    }
    prevCreateOpened.current = create.opened;
  }, [create.opened]);

  // Server-side fetch: satu-satunya sumber data tabel ini.
  const fetchData = async (nextParams) => {
    const thisRequest = ++requestId.current;
    setLoading(true);

    try {
      const response = await axios.get(route("projects.tasks.table.data", project.id), {
        params: nextParams,
      });

      if (thisRequest !== requestId.current) return; // response basi (ada request lebih baru)

      setPage(response.data);

      const url = new URL(route("projects.tasks.table", project.id));
      Object.entries(nextParams).forEach(([key, value]) => {
        if (value !== "" && value !== null && value !== undefined) {
          url.searchParams.set(key, value);
        }
      });
      window.history.replaceState({}, "", url.toString());
    } catch (e) {
      console.error(e);
    } finally {
      if (thisRequest === requestId.current) setLoading(false);
    }
  };

  const updateParams = (patch) => {
    const next = { ...params, ...patch };
    setParams(next);
    fetchData(next);
  };

  // load pertama kali halaman dibuka
  useEffect(() => {
    fetchData(params);
  }, []);

  // debounce search
  useEffect(() => {
    const timeout = setTimeout(() => {
      if (searchInput !== params.search) {
        updateParams({ search: searchInput, page: 1 });
      }
    }, 400);
    return () => clearTimeout(timeout);
  }, [searchInput]);

  const handleSort = (field) => {
    const direction = params.sort === field && params.direction === "asc" ? "desc" : "asc";
    updateParams({ sort: field, direction, page: 1 });
  };

  const handleDateRangeChange = (value) => {
    updateParams({
      created_from: value[0] ? dayjs(value[0]).format("YYYY-MM-DD") : "",
      created_to: value[1] ? dayjs(value[1]).format("YYYY-MM-DD") : "",
      page: 1,
    });
  };

  const handleDelete = (task) => {
    openConfirmModal({
      type: "danger",
      title: "Hapus Pelayanan",
      content: "Apakah anda yakin menghapus pelayanan ini?",
      confirmLabel: "Hapus",
      confirmProps: { color: "red" },
      onConfirm: () =>
        router.delete(route("projects.tasks.destroy", [task.project_id, task.id]), {
          preserveScroll: true,
          onSuccess: () => fetchData(params),
        }),
    });
  };

  const handleEdit = (task) => {
    const handleEdit = async (task) => {
      try {
        const res = await axios.get(route("projects.tasks.detail-json", [task.project_id, task.id]));
        addTask(res.data);
        openEditTask(res.data);
      } catch (e) {
        console.error(e);
      }
    };
  };

  const userOptions = usersWithAccessToProject.map((u) => ({
    value: u.id.toString(),
    label: u.name,
  }));

  const dateRangeValue = [
    params.created_from ? new Date(params.created_from) : null,
    params.created_to ? new Date(params.created_to) : null,
  ];

  const rows = (page?.data || []).map((task) => {
    const statusName = task.task_group?.name || "-";
    const statusColor = STATUS_COLORS[statusName.toLowerCase()] || "gray";

    return (
      <Table.Tr key={task.id}>
        <Table.Td>{task.row_number}</Table.Td>
        <Table.Td>{task.name}</Table.Td>
        <Table.Td>{task.code}</Table.Td>
        <Table.Td>{task.created_by_user?.name || "-"}</Table.Td>
        <Table.Td>{task.assigned_to_user?.name || "-"}</Table.Td>
        <Table.Td>
          <Badge color={statusColor} variant="light">
            {statusName}
          </Badge>
        </Table.Td>
        <Table.Td>{dayjs(task.created_at).format("DD-MM-YYYY")}</Table.Td>
        <Table.Td>{task.due_on ? dayjs(task.due_on).format("DD-MM-YYYY") : "-"}</Table.Td>
        <Table.Td>
          <Menu withArrow position="bottom-end" shadow="md">
            <Menu.Target>
              <ActionIcon variant="subtle" color="gray">
                <IconDots style={{ width: rem(18), height: rem(18) }} stroke={1.5} />
              </ActionIcon>
            </Menu.Target>
            <Menu.Dropdown>
              {can("edit task") && (
                <Menu.Item
                  leftSection={<IconPencil style={{ width: rem(16), height: rem(16) }} stroke={1.5} />}
                  onClick={() => handleEdit(task)}
                >
                  Edit
                </Menu.Item>
              )}
              {can("archive task") && (
                <Menu.Item
                  color="red"
                  leftSection={<IconTrash style={{ width: rem(16), height: rem(16) }} stroke={1.5} />}
                  onClick={() => handleDelete(task)}
                >
                  Hapus
                </Menu.Item>
              )}
            </Menu.Dropdown>
          </Menu>
        </Table.Td>
      </Table.Tr>
    );
  });

  return (
    <>
      <Grid justify="space-between" align="center" mb="lg">
        <Grid.Col span="content">
          <Title order={1}>{project.name}</Title>
        </Grid.Col>
        <Grid.Col span="content">
          {can("create task") && (
            <Button leftSection={<IconPlus size={16} />} onClick={() => openCreateTask()}>
              Tambah Pelayanan
            </Button>
          )}
        </Grid.Col>
      </Grid>

      <Grid align="flex-end" mb="lg" gutter="md">
        <Grid.Col span={{ base: 12, sm: 4 }}>
          <TextInput
            label="Cari"
            placeholder="Cari nama, nomor pelayanan, identitas..."
            leftSection={<IconSearch style={{ width: rem(16), height: rem(16) }} />}
            value={searchInput}
            onChange={(e) => setSearchInput(e.currentTarget.value)}
          />
        </Grid.Col>

        <Grid.Col span={{ base: 12, sm: 3 }}>
          <Select
            label="Penerima Pelayanan"
            placeholder="Semua pembuat"
            searchable
            clearable
            data={userOptions}
            value={params.created_by_user_id || null}
            onChange={(value) => updateParams({ created_by_user_id: value || "", page: 1 })}
          />
        </Grid.Col>

        <Grid.Col span={{ base: 12, sm: 3 }}>
          <Select
            label="Penerima Tugas"
            placeholder="Semua penerima tugas"
            searchable
            clearable
            data={userOptions}
            value={params.assigned_to_user_id || null}
            onChange={(value) => updateParams({ assigned_to_user_id: value || "", page: 1 })}
          />
        </Grid.Col>

        <Grid.Col span={{ base: 12, sm: 2 }}>
          <DatePickerInput
            type="range"
            label="Tanggal Masuk"
            placeholder="Pilih rentang tanggal"
            valueFormat="DD MMM YYYY"
            clearable
            value={dateRangeValue}
            onChange={handleDateRangeChange}
          />
        </Grid.Col>
      </Grid>

      <div style={{ position: "relative" }}>
        <LoadingOverlay visible={loading} zIndex={5} overlayProps={{ radius: "sm", blur: 1 }} />

        <Table.ScrollContainer minWidth={900}>
          <Table striped highlightOnHover verticalSpacing="sm">
            <Table.Thead>
              <Table.Tr>
                <Table.Th style={{ width: 60 }}>No</Table.Th>
                <Th label="Nama Pemohon" field="name" params={params} onSort={handleSort} />
                <Th label="Nomor Pelayanan" field="code" params={params} onSort={handleSort} />
                <Table.Th>Penerima Pelayanan</Table.Th>
                <Table.Th>Penerima Tugas</Table.Th>
                <Table.Th>Status</Table.Th>
                <Th label="Tanggal Masuk" field="created_at" params={params} onSort={handleSort} />
                <Th label="Batas Waktu" field="due_on" params={params} onSort={handleSort} />
                <Table.Th style={{ width: 70 }}>Aksi</Table.Th>
              </Table.Tr>
            </Table.Thead>
            <Table.Tbody>
              {rows.length ? (
                rows
              ) : (
                <Table.Tr>
                  <Table.Td colSpan={9}>
                    <Center py="xl">
                      <Text c="dimmed">
                        {loading ? " " : "Tidak ada pelayanan yang ditemukan."}
                      </Text>
                    </Center>
                  </Table.Td>
                </Table.Tr>
              )}
            </Table.Tbody>
          </Table>
        </Table.ScrollContainer>
      </div>

      {page && page.last_page > 1 && (
        <Group justify="flex-end" mt="lg">
          <Pagination
            total={page.last_page}
            value={page.current_page}
            onChange={(value) => updateParams({ page: value })}
          />
        </Group>
      )}

      {page && page.last_page > 1 && (
        <Group justify="flex-end" mt="lg">
          <Pagination
            total={page.last_page}
            value={page.current_page}
            onChange={(value) => updateParams({ page: value })}
          />
        </Group>
      )}

      {can("create task") && <CreateTaskDrawer />}
      <EditTaskDrawer />
    </>
  );
}

TableIndex.layout = (page) => <Layout title={page.props.project?.name}>{page}</Layout>;