import useForm from "@/hooks/useForm";
import Layout from "@/layouts/MainLayout";
import { redirectTo } from "@/utils/route";
import { Anchor, Breadcrumbs, Grid, Title } from "@mantine/core";
import MasterJabatanForm from "./Form";

const MasterJabatanCreate = () => {
  const [form, submit, updateValue] = useForm("post", route("settings.master-jabatans.store"), {
    code: "",
    name: "",
  });

  return (
    <>
      <Breadcrumbs fz={14} mb={30}>
        <Anchor href="#" onClick={() => redirectTo("settings.master-jabatans.index")} fz={14}>
          Jabatan
        </Anchor>
        <div>Tambah</div>
      </Breadcrumbs>

      <Grid justify="space-between" align="flex-end" gutter="xl" mb="lg">
        <Grid.Col span="auto">
          <Title order={1}>Tambah jabatan</Title>
        </Grid.Col>
      </Grid>

      <MasterJabatanForm form={form} submit={submit} updateValue={updateValue} submitLabel="Tambah" />
    </>
  );
};

MasterJabatanCreate.layout = (page) => <Layout title="Tambah jabatan">{page}</Layout>;

export default MasterJabatanCreate;
