import ActionButton from "@/components/ActionButton";
import BackButton from "@/components/BackButton";
import useForm from "@/hooks/useForm";
import ContainerBox from "@/layouts/ContainerBox";
import Layout from "@/layouts/MainLayout";
import { redirectTo } from "@/utils/route";
import { usePage } from "@inertiajs/react";
import {
  Anchor,
  Breadcrumbs,
  Grid,
  Group,
  NumberInput,
  Textarea,
  TextInput,
  Title,
} from "@mantine/core";

const JenisPelayananEdit = () => {
  const { item } = usePage().props;

  const [form, submit, updateValue] = useForm("post", route("settings.jenis-pelayanans.update", item.id), {
    _method: "put",
    name: item.name || "",
    jumlah_hari: item.jumlah_hari ?? "",
    keterangan: item.keterangan || "",
  });

  return (
    <>
      <Breadcrumbs fz={14} mb={30}>
        <Anchor href="#" onClick={() => redirectTo("settings.jenis-pelayanans.index")} fz={14}>
          Jenis Pelayanan
        </Anchor>
        <div>Edit</div>
      </Breadcrumbs>

      <Grid justify="space-between" align="flex-end" gutter="xl" mb="lg">
        <Grid.Col span="auto">
          <Title order={1}>Edit jenis pelayanan</Title>
        </Grid.Col>
        <Grid.Col span="content"></Grid.Col>
      </Grid>

      <ContainerBox maw={500}>
        <form onSubmit={submit}>
          <TextInput
            label="Kode"
            description="Kode tidak dapat diubah karena sudah dipakai sebagai acuan pelayanan."
            disabled
            value={item.code}
          />
          <TextInput
            label="Nama"
            placeholder="Nama jenis pelayanan"
            required
            mt="md"
            value={form.data.name}
            onChange={(e) => updateValue("name", e.target.value)}
            error={form.errors.name}
          />
          <NumberInput
            label="Jumlah Hari"
            placeholder="Kosongkan jika batas waktu dipilih manual"
            description="Batas waktu pelayanan = tanggal pelayanan dibuat + jumlah hari."
            mt="md"
            min={0}
            max={3650}
            allowDecimal={false}
            allowNegative={false}
            suffix=" hari"
            value={form.data.jumlah_hari}
            onChange={(value) => updateValue("jumlah_hari", value)}
            error={form.errors.jumlah_hari}
          />
          <Textarea
            label="Keterangan"
            placeholder="Keterangan jenis pelayanan"
            mt="md"
            autosize
            minRows={3}
            value={form.data.keterangan}
            onChange={(e) => updateValue("keterangan", e.target.value)}
            error={form.errors.keterangan}
          />

          <Group justify="space-between" mt="xl">
            <BackButton route="settings.jenis-pelayanans.index" />
            <ActionButton loading={form.processing}>Simpan</ActionButton>
          </Group>
        </form>
      </ContainerBox>
    </>
  );
};

JenisPelayananEdit.layout = (page) => <Layout title="Edit jenis pelayanan">{page}</Layout>;

export default JenisPelayananEdit;
