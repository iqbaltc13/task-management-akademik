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

const JenisPelayanansIndex = () => {
  const { items } = usePage().props;

  const columns = prepareColumns([
    { label: "Kode", column: "code" },
    { label: "Nama", column: "name" },
    { label: "Jumlah Hari", column: "jumlah_hari" },
    { label: "Keterangan", sortable: false },
    {
      label: "Aksi",
      sortable: false,
      visible: actionColumnVisibility("jenis pelayanan"),
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
            <SearchInput placeholder="Cari jenis pelayanan" search={search} />
            <ArchivedFilterButton />
          </Group>
        </Grid.Col>
        <Grid.Col span="content">
          {can("create jenis pelayanan") && (
            <Button
              leftSection={<IconPlus size={14} />}
              radius="xl"
              onClick={() => redirectTo("settings.jenis-pelayanans.create")}
            >
              Tambah
            </Button>
          )}
        </Grid.Col>
      </Grid>

      <Table.ScrollContainer minWidth={700} my="lg">
        <Table verticalSpacing="sm">
          <TableHead columns={columns} sort={sort} />
          <Table.Tbody>{rows}</Table.Tbody>
        </Table>
      </Table.ScrollContainer>

      <Pagination current={items.meta.current_page} pages={items.meta.last_page} />
    </>
  );
};

JenisPelayanansIndex.layout = (page) => <Layout title="Jenis Pelayanan">{page}</Layout>;

export default JenisPelayanansIndex;
