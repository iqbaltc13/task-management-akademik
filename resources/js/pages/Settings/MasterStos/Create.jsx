import useForm from "@/hooks/useForm";
import Layout from "@/layouts/MainLayout";
import { redirectTo } from "@/utils/route";
import { usePage } from "@inertiajs/react";
import { Anchor, Breadcrumbs, Grid, Title } from "@mantine/core";
import MasterStoForm from "./Form";

const MasterStoCreate = () => {
  const { options } = usePage().props;
  const [form, submit, updateValue] = useForm("post", route("settings.master-stos.store"), {
    code: "",
    code_parent: null,
    name: "",
    id_unit: null,
    id_sub_unit: null,
    is_pimpinan: false,
    level: 1,
    id_jabatan: null,
  });

  return (
    <>
      <Breadcrumbs fz={14} mb={30}>
        <Anchor href="#" onClick={() => redirectTo("settings.master-stos.index")} fz={14}>
          Struktur Organisasi
        </Anchor>
        <div>Tambah</div>
      </Breadcrumbs>

      <Grid justify="space-between" align="flex-end" gutter="xl" mb="lg">
        <Grid.Col span="auto">
          <Title order={1}>Tambah struktur organisasi</Title>
        </Grid.Col>
      </Grid>

      <MasterStoForm form={form} submit={submit} updateValue={updateValue} submitLabel="Tambah" options={options} />
    </>
  );
};

MasterStoCreate.layout = (page) => <Layout title="Tambah struktur organisasi">{page}</Layout>;

export default MasterStoCreate;
