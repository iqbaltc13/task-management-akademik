import Layout from "@/layouts/MainLayout";
import { usePage } from "@inertiajs/react";
import { DatePickerInput } from "@mantine/dates";
import {
  Badge,
  Button,
  Center,
  Grid,
  Group,
  LoadingOverlay,
  Pagination,
  Select,
  Table,
  Tabs,
  Text,
  TextInput,
  Title,
  UnstyledButton,
  rem,
} from "@mantine/core";
import {
  IconArrowsSort,
  IconFileSpreadsheet,
  IconFileTypePdf,
  IconSearch,
  IconSortAscending,
  IconSortDescending,
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

const TABS = [
  { value: "active", label: "Pelayanan Aktif" },
  { value: "completed", label: "Pelayanan Selesai" },
  { value: "received", label: "Pelayanan Saya Terima" },
  { value: "transferred", label: "Pelayanan Dialihkan" },
];

const DEFAULT_PARAMS = {
  type: "active",
  search: "",
  sort: "created_at",
  direction: "desc",
  project_id: "",
  created_from: "",
  created_to: "",
  page: 1,
  per_page: 15,
};

function Th({ label, field, params, onSort }) {
  const active = params.sort === field;
  const Icon = active
    ? params.direction === "asc" ? IconSortAscending : IconSortDescending
    : IconArrowsSort;

  return (
    <Table.Th>
      <UnstyledButton onClick={() => onSort(field)} style={{ display: "flex", alignItems: "center", gap: 4 }}>
        <Text fw={600} fz="sm">{label}</Text>
        <Icon style={{ width: rem(14), height: rem(14) }} opacity={active ? 1 : 0.4} />
      </UnstyledButton>
    </Table.Th>
  );
}

export default function ListIndex() {
  const { projects } = usePage().props;

  const [params, setParams] = useState({ ...DEFAULT_PARAMS });
  const [searchInput, setSearchInput] = useState("");
  const [page, setPage] = useState(null);
  const [loading, setLoading] = useState(true);
  const requestId = useRef(0);

  const fetchData = async (nextParams) => {
    const thisRequest = ++requestId.current;
    setLoading(true);
    try {
      const response = await axios.get(route("my-work.tasks.list.data"), { params: nextParams });
      if (thisRequest !== requestId.current) return;
      setPage(response.data);
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

  useEffect(() => { fetchData(params); }, []);

  useEffect(() => {
    const timeout = setTimeout(() => {
      if (searchInput !== params.search) updateParams({ search: searchInput, page: 1 });
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

  const handleExport = (format) => {
    const url = new URL(route(`my-work.tasks.list.export.${format}`));
    Object.entries(params).forEach(([key, value]) => {
      if (value !== "" && value !== null && value !== undefined) {
        url.searchParams.set(key, value);
      }
    });
    window.location.href = url.toString();
  };

  const projectOptions = (projects || []).map((p) => ({ value: p.id.toString(), label: p.name }));

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
          <Badge color={statusColor} variant="light">{statusName}</Badge>
        </Table.Td>
        <Table.Td>{dayjs(task.created_at).format("DD-MM-YYYY")}</Table.Td>
        <Table.Td>{task.due_on ? dayjs(task.due_on).format("DD-MM-YYYY") : "-"}</Table.Td>
        <Table.Td>{task.project?.name || "-"}</Table.Td>
      </Table.Tr>
    );
  });

  return (
    <>
      <Grid justify="space-between" align="center" mb="lg">
        <Grid.Col span="content">
          <Title order={1}>Pelayanan Saya</Title>
        </Grid.Col>
        <Grid.Col span="content">
          <Group gap="xs">
            <Button variant="light" leftSection={<IconFileSpreadsheet size={16} />} onClick={() => handleExport("xlsx")}>
              Cetak Excel
            </Button>
            <Button variant="light" leftSection={<IconFileTypePdf size={16} />} onClick={() => handleExport("pdf")}>
              Cetak PDF
            </Button>
          </Group>
        </Grid.Col>
      </Grid>

      <Tabs value={params.type} onChange={(value) => updateParams({ type: value, page: 1 })} mb="lg">
        <Tabs.List>
          {TABS.map((t) => (
            <Tabs.Tab key={t.value} value={t.value}>{t.label}</Tabs.Tab>
          ))}
        </Tabs.List>
      </Tabs>

      <Grid align="flex-end" mb="lg" gutter="md">
        <Grid.Col span={{ base: 12, sm: 5 }}>
          <TextInput
            label="Cari"
            placeholder="Cari nama, nomor pelayanan, identitas..."
            leftSection={<IconSearch style={{ width: rem(16), height: rem(16) }} />}
            value={searchInput}
            onChange={(e) => setSearchInput(e.currentTarget.value)}
          />
        </Grid.Col>

        <Grid.Col span={{ base: 12, sm: 4 }}>
          <Select
            label="Periode Pelayanan"
            placeholder="Semua periode"
            searchable
            clearable
            data={projectOptions}
            value={params.project_id || null}
            onChange={(value) => updateParams({ project_id: value || "", page: 1 })}
          />
        </Grid.Col>

        <Grid.Col span={{ base: 12, sm: 3 }}>
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
                <Table.Th>Periode Pelayanan</Table.Th>
              </Table.Tr>
            </Table.Thead>
            <Table.Tbody>
              {rows.length ? rows : (
                <Table.Tr>
                  <Table.Td colSpan={9}>
                    <Center py="xl">
                      <Text c="dimmed">{loading ? " " : "Tidak ada pelayanan yang ditemukan."}</Text>
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
    </>
  );
}

ListIndex.layout = (page) => <Layout title="Pelayanan Saya">{page}</Layout>;