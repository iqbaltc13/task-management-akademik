import TableRowActions from "@/components/TableRowActions";
import { Table, Text } from "@mantine/core";

export default function TableRow({ item }) {
  return (
    <Table.Tr key={item.id}>
      <Table.Td>
        <Text fz="sm">{item.code}</Text>
      </Table.Td>
      <Table.Td>
        <Text fz="sm">{item.name}</Text>
      </Table.Td>
      {(can("edit master jabatan") || can("archive master jabatan") || can("restore master jabatan")) && (
        <Table.Td w={100}>
          <TableRowActions
            item={item}
            editRoute="settings.master-jabatans.edit"
            editPermission="edit master jabatan"
            archivePermission="archive master jabatan"
            restorePermission="restore master jabatan"
            archive={{
              route: "settings.master-jabatans.destroy",
              title: "Arsipkan jabatan",
              content: "Yakin ingin mengarsipkan jabatan ini?",
              confirmLabel: "Arsipkan",
            }}
            restore={{
              route: "settings.master-jabatans.restore",
              title: "Kembalikan jabatan",
              content: "Yakin ingin mengembalikan jabatan ini?",
              confirmLabel: "Kembalikan",
            }}
          />
        </Table.Td>
      )}
    </Table.Tr>
  );
}
