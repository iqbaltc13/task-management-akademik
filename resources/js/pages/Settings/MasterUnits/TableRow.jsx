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
      {(can("edit master unit") || can("archive master unit") || can("restore master unit")) && (
        <Table.Td w={100}>
          <TableRowActions
            item={item}
            editRoute="settings.master-units.edit"
            editPermission="edit master unit"
            archivePermission="archive master unit"
            restorePermission="restore master unit"
            archive={{
              route: "settings.master-units.destroy",
              title: "Arsipkan unit",
              content: "Yakin ingin mengarsipkan unit ini?",
              confirmLabel: "Arsipkan",
            }}
            restore={{
              route: "settings.master-units.restore",
              title: "Kembalikan unit",
              content: "Yakin ingin mengembalikan unit ini?",
              confirmLabel: "Kembalikan",
            }}
          />
        </Table.Td>
      )}
    </Table.Tr>
  );
}
