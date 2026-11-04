import useForm from "@/hooks/useForm";
import Layout from "@/layouts/MainLayout";
import { redirectTo } from "@/utils/route";
import { usePage } from "@inertiajs/react";
import { Anchor, Breadcrumbs, Grid, Title } from "@mantine/core";
import MasterSubUnitForm from "./Form";

const MasterSubUnitEdit = () => {
  const { item, options } = usePage().props;
  const [form, submit, updateValue] = useForm("post", route("settings.master-sub-units.update", item.id), {
    _method: "put",
    code: item.code ?? "",
    name: item.name ?? "",
    master_unit_code: item.master_unit_code ?? null,
  });

  return (
    <>
      <Breadcrumbs fz={14} mb={30}>
        <Anchor href="#" onClick={() => redirectTo("settings.master-sub-units.index")} fz={14}>
          Sub Unit
        </Anchor>
        <div>Edit</div>
      </Breadcrumbs>

      <Grid justify="space-between" align="flex-end" gutter="xl" mb="lg">
        <Grid.Col span="auto">
          <Title order={1}>Edit sub unit</Title>
        </Grid.Col>
      </Grid>

      <MasterSubUnitForm form={form} submit={submit} updateValue={updateValue} submitLabel="Simpan" options={options} />
    </>
  );
};

MasterSubUnitEdit.layout = (page) => <Layout title="Edit sub unit">{page}</Layout>;

export default MasterSubUnitEdit;
