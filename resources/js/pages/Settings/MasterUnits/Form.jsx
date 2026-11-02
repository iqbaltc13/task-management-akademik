import ActionButton from "@/components/ActionButton";
import BackButton from "@/components/BackButton";
import ContainerBox from "@/layouts/ContainerBox";
import { Group, TextInput } from "@mantine/core";

export default function MasterUnitForm({ form, submit, updateValue, submitLabel }) {
  return (
    <ContainerBox maw={500}>
      <form onSubmit={submit}>
        <TextInput
          label="Kode"
          placeholder="Kode unit"
          required
          value={form.data.code}
          onChange={(e) => updateValue("code", e.target.value)}
          error={form.errors.code}
        />
        <TextInput
          label="Nama"
          placeholder="Nama unit"
          required
          mt="md"
          value={form.data.name}
          onChange={(e) => updateValue("name", e.target.value)}
          error={form.errors.name}
        />

        <Group justify="space-between" mt="xl">
          <BackButton route="settings.master-units.index" />
          <ActionButton loading={form.processing}>{submitLabel}</ActionButton>
        </Group>
      </form>
    </ContainerBox>
  );
}