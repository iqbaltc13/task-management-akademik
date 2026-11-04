import ActionButton from "@/components/ActionButton";
import BackButton from "@/components/BackButton";
import ContainerBox from "@/layouts/ContainerBox";
import { Group, Select, TextInput } from "@mantine/core";

export default function MasterSubUnitForm({ form, submit, updateValue, submitLabel, options }) {
  return (
    <ContainerBox maw={500}>
      <form onSubmit={submit}>
        <TextInput
          label="Kode"
          placeholder="Kode sub unit"
          required
          value={form.data.code}
          onChange={(e) => updateValue("code", e.target.value)}
          error={form.errors.code}
        />
        <TextInput
          label="Nama"
          placeholder="Nama sub unit"
          required
          mt="md"
          value={form.data.name}
          onChange={(e) => updateValue("name", e.target.value)}
          error={form.errors.name}
        />
        <Select
          label="Unit"
          placeholder="Pilih unit"
          required
          searchable
          mt="md"
          data={options.units}
          nothingFoundMessage="Unit tidak ditemukan"
          value={form.data.master_unit_code}
          onChange={(value) => updateValue("master_unit_code", value)}
          error={form.errors.master_unit_code}
        />

        <Group justify="space-between" mt="xl">
          <BackButton route="settings.master-sub-units.index" />
          <ActionButton loading={form.processing}>{submitLabel}</ActionButton>
        </Group>
      </form>
    </ContainerBox>
  );
}
