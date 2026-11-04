import useForm from "@/hooks/useForm";
import Layout from "@/layouts/MainLayout";
import { redirectTo } from "@/utils/route";
import { usePage } from "@inertiajs/react";
import { Anchor, Breadcrumbs, Grid, Title } from "@mantine/core";
import MasterJabatanForm from "./Form";

const MasterJabatanEdit = () => {
  const { item } = usePage().props;
  const [form, submit, updateValue] = useForm("post", route("settings.master-jabatans.update", item.id), {
    _method: "put",
    code: item.code ?? "",
    name: item.name ?? "",
  });

  return (
    <>
      <Breadcrumbs fz={14} mb={30}>
        <Anchor href="#" onClick={() => redirectTo("settings.master-jabatans.index")} fz={14}>
          Jabatan
        </Anchor>
        <div>Edit</div>
      </Breadcrumbs>

      <Grid justify="space-between" align="flex-end" gutter="xl" mb="lg">
        <Grid.Col span="auto">
          <Title order={1}>Edit jabatan</Title>
        </Grid.Col>
      </Grid>

      <MasterJabatanForm form={form} submit={submit} updateValue={updateValue} submitLabel="Simpan" />
    </>
  );
};

MasterJabatanEdit.layout = (page) => <Layout title="Edit jabatan">{page}</Layout>;

export default MasterJabatanEdit;
