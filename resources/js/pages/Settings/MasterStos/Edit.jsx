import useForm from "@/hooks/useForm";
import Layout from "@/layouts/MainLayout";
import { redirectTo } from "@/utils/route";
import { usePage } from "@inertiajs/react";
import { Anchor, Breadcrumbs, Grid, Title } from "@mantine/core";
import MasterStoForm from "./Form";

const MasterStoEdit = () => {
  const { item, options } = usePage().props;
  const [form, submit, updateValue] = useForm("post", route("settings.master-stos.update", item.id), {
    _method: "put",
    code: item.code ?? "",
    code_parent: item.code_parent ?? null,
    name: item.name ?? "",
    id_unit: item.id_unit ? String(item.id_unit) : null,
    id_sub_unit: item.id_sub_unit ? String(item.id_sub_unit) : null,
    is_pimpinan: !!item.is_pimpinan,
    level: item.level ?? 1,
    id_jabatan: item.id_jabatan ? String(item.id_jabatan) : null,
  });

  return (
    <>
      <Breadcrumbs fz={14} mb={30}>
        <Anchor href="#" onClick={() => redirectTo("settings.master-stos.index")} fz={14}>
          Struktur Organisasi
        </Anchor>
        <div>Edit</div>
      </Breadcrumbs>

      <Grid justify="space-between" align="flex-end" gutter="xl" mb="lg">
        <Grid.Col span="auto">
          <Title order={1}>Edit struktur organisasi</Title>
        </Grid.Col>
      </Grid>

      <MasterStoForm form={form} submit={submit} updateValue={updateValue} submitLabel="Simpan" options={options} />
    </>
  );
};

MasterStoEdit.layout = (page) => <Layout title="Edit struktur organisasi">{page}</Layout>;

export default MasterStoEdit;
