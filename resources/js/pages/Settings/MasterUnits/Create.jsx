import useForm from "@/hooks/useForm";
import Layout from "@/layouts/MainLayout";
import { redirectTo } from "@/utils/route";
import { Anchor, Breadcrumbs, Grid, Title } from "@mantine/core";
import MasterUnitForm from "./Form";

const MasterUnitCreate = () => {
  const [form, submit, updateValue] = useForm("post", route("settings.master-units.store"), {
    code: "",
    name: "",
  });

  return (
    <>
      <Breadcrumbs fz={14} mb={30}>
        <Anchor href="#" onClick={() => redirectTo("settings.master-units.index")} fz={14}>
          Unit
        </Anchor>
        <div>Tambah</div>
      </Breadcrumbs>

      <Grid justify="space-between" align="flex-end" gutter="xl" mb="lg">
        <Grid.Col span="auto">
          <Title order={1}>Tambah unit</Title>
        </Grid.Col>
      </Grid>

      <MasterUnitForm form={form} submit={submit} updateValue={updateValue} submitLabel="Tambah" />
    </>
  );
};

MasterUnitCreate.layout = (page) => <Layout title="Tambah unit">{page}</Layout>;

export default MasterUnitCreate;
