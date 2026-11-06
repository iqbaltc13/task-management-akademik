import Layout from "@/layouts/MainLayout";
import {
  Badge,
  Button,
  Center,
  Divider,
  Grid,
  Group,
  LoadingOverlay,
  Modal,
  Pagination,
  Stack,
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
  IconCheck,
  IconFileSpreadsheet,   // <-- tambah
  IconFileTypePdf,       // <-- tambah
  IconSearch,
  IconSortAscending,
  IconSortDescending,
} from "@tabler/icons-react";
import axios from "axios";
import dayjs from "dayjs";
import { useEffect, useRef, useState } from "react";
import { notifications } from "@mantine/notifications";


const STATUS_COLORS = {
  diajukan: "grape",
  diproses: "blue",
  "tidak dilanjutakan": "gray",
  ditolak: "red",
  selesai: "green",
};

const TABS = [
  { value: "pending", label: "Belum Diparaf" },
  { value: "approved", label: "Sudah Diparaf" },
  { value: "all", label: "Semua" },
];

const DEFAULT_PARAMS = {
  status: "pending",
  search: "",
  sort: "created_at",
  direction: "desc",
  page: 1,
  per_page: 15,
};

// Deskripsi pelayanan berupa HTML (rich text); tampilkan sebagai teks biasa agar aman.
const htmlToText = (html) =>
  html ? new DOMParser().parseFromString(html, "text/html").body.textContent : "";

const formatDate = (value) => (value ? dayjs(value).format("DD-MM-YYYY") : "-");

function Th({ label, field, params, onSort }) {
  const active = params.sort === field;
  const Icon = active
    ? params.direction === "asc"
      ? IconSortAscending
      : IconSortDescending
    : IconArrowsSort;

  return (
    <Table.Th>
      <UnstyledButton
        onClick={() => onSort(field)}
        style={{ display: "flex", alignItems: "center", gap: 4 }}
      >
        <Text fw={600} fz="sm">
          {label}
        </Text>
        <Icon style={{ width: rem(14), height: rem(14) }} opacity={active ? 1 : 0.4} />
      </UnstyledButton>
    </Table.Th>
  );
}

function DetailRow({ label, children }) {
  return (
    <Grid gutter="xs">
      <Grid.Col span={{ base: 12, sm: 4 }}>
        <Text fz="sm" c="dimmed">
          {label}
        </Text>
      </Grid.Col>
      <Grid.Col span={{ base: 12, sm: 8 }}>
        <Text fz="sm" style={{ whiteSpace: "pre-wrap" }}>
          {children || "-"}
        </Text>
      </Grid.Col>
    </Grid>
  );
}

export default function ParafIndex() {
  const [params, setParams] = useState(DEFAULT_PARAMS);
  const [searchInput, setSearchInput] = useState("");
  const [page, setPage] = useState(null);
  const [loading, setLoading] = useState(true);
  const requestId = useRef(0);

  const [modalOpen, setModalOpen] = useState(false);
  const [detail, setDetail] = useState(null);
  const [detailLoading, setDetailLoading] = useState(false);
  const [approving, setApproving] = useState(false);

  const fetchData = async (nextParams) => {
    const thisRequest = ++requestId.current;
    setLoading(true);
    try {
      const response = await axios.get(route("my-work.paraf.data"), { params: nextParams });
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

  useEffect(() => {
    fetchData(params);
  }, []);

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

  const handleExport = (format) => {
    const url = new URL(route(`my-work.paraf.export.${format}`));
    ["status", "search", "sort", "direction"].forEach((key) => {
      if (params[key] !== "" && params[key] !== null && params[key] !== undefined) {
        url.searchParams.set(key, params[key]);
      }
    });
    window.location.href = url.toString();
  };

  const openDetail = async (task) => {
    setModalOpen(true);
    setDetail(null);
    setDetailLoading(true);
    try {
      const response = await axios.get(route("my-work.paraf.show", task.id));
      setDetail(response.data);
    } catch (e) {
      console.error(e);
      setModalOpen(false);
    } finally {
      setDetailLoading(false);
    }
  };

  const closeDetail = () => {
    setModalOpen(false);
    setDetail(null);
  };

    const approve = async () => {
    if (!detail) return;
    setApproving(true);
    try {
      await axios.post(route("my-work.paraf.approve", detail.id));

      notifications.show({
        title: "Berhasil",
        message: "Sukses memparaf pelayanan",
        color: "green",
        icon: <IconCheck size={16} />,
      });

      closeDetail();
      fetchData(params);
    } catch (e) {
      console.error(e);

      notifications.show({
        title: "Gagal",
        message: e.response?.data?.message || "Gagal memparaf pelayanan",
        color: "red",
      });
    } finally {
      setApproving(false);
    }
  };

  const rows = (page?.data || []).map((task, index) => {
    const statusName = task.task_group?.name || "-";
    const statusColor = STATUS_COLORS[statusName.toLowerCase()] || "gray";

    return (
      <Table.Tr key={task.id}>
        <Table.Td>{(page.from || 1) + index}</Table.Td>
        <Table.Td>{task.name}</Table.Td>
        <Table.Td>{task.code}</Table.Td>
        <Table.Td>{task.created_by_user?.name || "-"}</Table.Td>
        <Table.Td>{task.assigned_to_user?.name || "-"}</Table.Td>
        <Table.Td>
          <Badge color={statusColor} variant="light">
            {statusName}
          </Badge>
        </Table.Td>
        <Table.Td>{formatDate(task.created_at)}</Table.Td>
        <Table.Td>
          <Badge color={task.is_approved ? "green" : "orange"} variant="light">
            {task.is_approved ? "Sudah diparaf" : "Belum diparaf"}
          </Badge>
        </Table.Td>
        <Table.Td>
          <Button
            size="xs"
            variant="light"
            leftSection={<IconCheck size={14} />}
            onClick={() => openDetail(task)}
          >
            Paraf
          </Button>
        </Table.Td>
      </Table.Tr>
    );
  });

  return (
    <>
      <Grid justify="space-between" align="center" mb="lg">
        <Grid.Col span="content">
          <Title order={1}>Paraf Pelayanan</Title>
        </Grid.Col>
        <Grid.Col span="content">
          <Group gap="xs">
            <Button
              variant="light"
              leftSection={<IconFileSpreadsheet size={16} />}
              onClick={() => handleExport("xlsx")}
            >
              Cetak Excel
            </Button>
            <Button
              variant="light"
              leftSection={<IconFileTypePdf size={16} />}
              onClick={() => handleExport("pdf")}
            >
              Cetak PDF
            </Button>
          </Group>
        </Grid.Col>
      </Grid>

      <Tabs value={params.status} onChange={(value) => updateParams({ status: value, page: 1 })} mb="lg">
        <Tabs.List>
          {TABS.map((t) => (
            <Tabs.Tab key={t.value} value={t.value}>
              {t.label}
            </Tabs.Tab>
          ))}
        </Tabs.List>
      </Tabs>

      <Grid align="flex-end" mb="lg" gutter="md">
        <Grid.Col span={{ base: 12, sm: 6 }}>
          <TextInput
            label="Cari"
            placeholder="Cari nama, nomor pelayanan, identitas..."
            leftSection={<IconSearch style={{ width: rem(16), height: rem(16) }} />}
            value={searchInput}
            onChange={(e) => setSearchInput(e.currentTarget.value)}
          />
        </Grid.Col>
      </Grid>

      <div style={{ position: "relative" }}>
        <LoadingOverlay visible={loading} zIndex={5} overlayProps={{ radius: "sm", blur: 1 }} />

        <Table.ScrollContainer minWidth={1000}>
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
                <Table.Th>Status Paraf</Table.Th>
                <Table.Th>Aksi</Table.Th>
              </Table.Tr>
            </Table.Thead>
            <Table.Tbody>
              {rows.length ? (
                rows
              ) : (
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

      <Modal opened={modalOpen} onClose={closeDetail} title="Detail Pelayanan" size="lg" centered>
        <div style={{ position: "relative", minHeight: 120 }}>
          <LoadingOverlay visible={detailLoading} />

          {detail && (
            <Stack gap="sm">
              <DetailRow label="Nama Pemohon">{detail.name}</DetailRow>
              <DetailRow label="Nomor Pelayanan">{detail.code}</DetailRow>
              <DetailRow label="Nomor Identitas">{detail.identity_number}</DetailRow>
              <DetailRow label="Jenis Pelayanan">{detail.jenis_pelayanan?.name}</DetailRow>
              <DetailRow label="Periode Pelayanan">{detail.project?.name}</DetailRow>
              <DetailRow label="Penerima Pelayanan">{detail.created_by_user?.name}</DetailRow>
              <DetailRow label="Penerima Tugas">{detail.assigned_to_user?.name}</DetailRow>
              <DetailRow label="Status">{detail.task_group?.name}</DetailRow>
              <DetailRow label="Tanggal Masuk">{formatDate(detail.created_at)}</DetailRow>
              <DetailRow label="Batas Waktu">{formatDate(detail.due_on)}</DetailRow>
              <DetailRow label="Deskripsi">{htmlToText(detail.description)}</DetailRow>
              <DetailRow label="Diparaf Oleh">{detail.approved_by_user?.name}</DetailRow>

              <Divider my="xs" />

              <Group justify="space-between">
                <Badge color={detail.is_approved ? "green" : "orange"} variant="light" size="lg">
                  {detail.is_approved ? "Sudah diparaf" : "Belum diparaf"}
                </Badge>

                <Group>
                  <Button variant="default" onClick={closeDetail}>
                    Tutup
                  </Button>
                  {!detail.is_approved && (
                    <Button leftSection={<IconCheck size={16} />} loading={approving} onClick={approve}>
                      Lakukan Paraf
                    </Button>
                  )}
                </Group>
              </Group>
            </Stack>
          )}
        </div>
      </Modal>
    </>
  );
}

ParafIndex.layout = (page) => <Layout title="Paraf Pelayanan">{page}</Layout>;