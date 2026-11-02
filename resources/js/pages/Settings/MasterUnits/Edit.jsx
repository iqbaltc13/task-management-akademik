import useForm from "@/hooks/useForm";
import Layout from "@/layouts/MainLayout";
import { redirectTo } from "@/utils/route";
import { usePage } from "@inertiajs/react";
import { Anchor, Breadcrumbs, Grid, Title } from "@mantine/core";
import MasterUnitForm from "./Form";

const MasterUnitEdit = () => {
  const { item } = usePage().props;
  const [form, submit, updateValue] = useForm("post", route("settings.master-units.update", item.id), {
    _method: "put",
    code: item.code ?? "",
    name: item.name ?? "",
  });

  return (
    <>
      <Breadcrumbs fz={14} mb={30}>
        <Anchor href="#" onClick={() => redirectTo("settings.master-units.index")} fz={14}>
          Unit
        </Anchor>
        <div>Edit</div>
      </Breadcrumbs>

      <Grid justify="space-between" align="flex-end" gutter="xl" mb="lg">
        <Grid.Col span="auto">
          <Title order={1}>Edit unit</Title>
        </Grid.Col>
      </Grid>

      <MasterUnitForm form={form} submit={submit} updateValue={updateValue} submitLabel="Simpan" />
    </>
  );
};

MasterUnitEdit.layout = (page) => <Layout title="Edit unit">{page}</Layout>;

export default MasterUnitEdit;