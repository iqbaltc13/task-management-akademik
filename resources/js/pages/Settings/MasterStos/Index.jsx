import ArchivedFilterButton from "@/components/ArchivedFilterButton";
import Pagination from "@/components/Pagination";
import SearchInput from "@/components/SearchInput";
import TableHead from "@/components/TableHead";
import TableRowEmpty from "@/components/TableRowEmpty";
import Layout from "@/layouts/MainLayout";
import { redirectTo, reloadWithQuery } from "@/utils/route";
import { actionColumnVisibility, prepareColumns } from "@/utils/table";
import { usePage } from "@inertiajs/react";
import { Button, Grid, Group, Table } from "@mantine/core";
import { IconPlus } from "@tabler/icons-react";
import TableRow from "./TableRow";

const MasterStoIndex = () => {
  const { items } = usePage().props;

  const columns = prepareColumns([
    { label: "Kode", column: "code" },
    { label: "Nama", column: "name" },
    { label: "Induk", sortable: false },
    { label: "Unit", sortable: false },
    { label: "Sub Unit", sortable: false },
    { label: "Jabatan", sortable: false },
    { label: "Level", column: "level" },
    { label: "Pimpinan", sortable: false },
    {
      label: "Aksi",
      sortable: false,
      visible: actionColumnVisibility("master sto"),
    },
  ]);

  const rows = items.data.length ? (
    items.data.map((item) => <TableRow item={item} key={item.id} />)
  ) : (
    <TableRowEmpty colSpan={columns.length} />
  );

  const search = (search) => reloadWithQuery({ search });
  const sort = (sort) => reloadWithQuery(sort);

  return (
    <>
      <Grid justify="space-between" align="center">
        <Grid.Col span="content">
          <Group>
            <SearchInput placeholder="Cari kode atau nama" search={search} />
            <ArchivedFilterButton />
          </Group>
        </Grid.Col>
        <Grid.Col span="content">
          {can("create master sto") && (
            <Button
              leftSection={<IconPlus size={14} />}
              radius="xl"
              onClick={() => redirectTo("settings.master-stos.create")}
            >
              Tambah
            </Button>
          )}
        </Grid.Col>
      </Grid>

      <Table.ScrollContainer minWidth={1100} my="lg">
        <Table verticalSpacing="sm">
          <TableHead columns={columns} sort={sort} />
          <Table.Tbody>{rows}</Table.Tbody>
        </Table>
      </Table.ScrollContainer>

      <Pagination current={items.meta.current_page} pages={items.meta.last_page} />
    </>
  );
};

MasterStoIndex.layout = (page) => <Layout title="Struktur Organisasi">{page}</Layout>;

export default MasterStoIndex;
