import ActionButton from "@/components/ActionButton";
import BackButton from "@/components/BackButton";
import ContainerBox from "@/layouts/ContainerBox";
import { Group, NumberInput, Select, Switch, TextInput } from "@mantine/core";
import { useMemo } from "react";

export default function MasterStoForm({ form, submit, updateValue, submitLabel, options }) {
  const subUnits = useMemo(
    () =>
      form.data.id_unit
        ? options.subUnits.filter((s) => String(s.unit_id) === String(form.data.id_unit))
        : [],
    [options.subUnits, form.data.id_unit]
  );

  // Level otomatis = level induk + 1 (tanpa induk = 1). Tetap bisa diubah manual.
  const onParentChange = (code) => {
    const parent = options.parents.find((p) => p.value === code);
    updateValue("code_parent", code);
    updateValue("level", parent ? parent.level + 1 : 1);
  };

  const onUnitChange = (value) => {
    updateValue("id_unit", value);
    updateValue("id_sub_unit", null);
  };

  return (
    <ContainerBox maw={600}>
      <form onSubmit={submit}>
        <TextInput
          label="Kode"
          placeholder="Kode struktur organisasi"
          required
          value={form.data.code}
          onChange={(e) => updateValue("code", e.target.value)}
          error={form.errors.code}
        />
        <TextInput
          label="Nama"
          placeholder="Nama struktur organisasi"
          required
          mt="md"
          value={form.data.name}
          onChange={(e) => updateValue("name", e.target.value)}
          error={form.errors.name}
        />
        <Select
          label="Induk"
          description="Kosongkan jika ini struktur tertinggi"
          placeholder="Pilih induk"
          searchable
          clearable
          mt="md"
          data={options.parents}
          nothingFoundMessage="Induk tidak ditemukan"
          value={form.data.code_parent}
          onChange={onParentChange}
          error={form.errors.code_parent}
        />
        <Select
          label="Unit"
          placeholder="Pilih unit"
          searchable
          clearable
          mt="md"
          data={options.units}
          nothingFoundMessage="Unit tidak ditemukan"
          value={form.data.id_unit}
          onChange={onUnitChange}
          error={form.errors.id_unit}
        />
        <Select
          label="Sub Unit"
          placeholder={form.data.id_unit ? "Pilih sub unit" : "Pilih unit terlebih dahulu"}
          searchable
          clearable
          disabled={!form.data.id_unit}
          mt="md"
          data={subUnits}
          nothingFoundMessage="Sub unit tidak ditemukan"
          value={form.data.id_sub_unit}
          onChange={(value) => updateValue("id_sub_unit", value)}
          error={form.errors.id_sub_unit}
        />
        <Select
          label="Jabatan"
          placeholder="Pilih jabatan"
          searchable
          clearable
          mt="md"
          data={options.jabatans}
          nothingFoundMessage="Jabatan tidak ditemukan"
          value={form.data.id_jabatan}
          onChange={(value) => updateValue("id_jabatan", value)}
          error={form.errors.id_jabatan}
        />
        <NumberInput
          label="Level"
          description="Semakin ke bawah (bawahan), angka semakin besar"
          required
          min={1}
          allowDecimal={false}
          allowNegative={false}
          mt="md"
          value={form.data.level}
          onChange={(value) => updateValue("level", value === "" ? "" : Number(value))}
          error={form.errors.level}
        />
        <Switch
          label="Pimpinan"
          mt="md"
          checked={!!form.data.is_pimpinan}
          onChange={(e) => updateValue("is_pimpinan", e.currentTarget.checked)}
          error={form.errors.is_pimpinan}
        />

        <Group justify="space-between" mt="xl">
          <BackButton route="settings.master-stos.index" />
          <ActionButton loading={form.processing}>{submitLabel}</ActionButton>
        </Group>
      </form>
    </ContainerBox>
  );
}
