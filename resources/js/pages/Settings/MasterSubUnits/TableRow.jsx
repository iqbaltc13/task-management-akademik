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
      <Table.Td>
        <Text fz="sm">{item.master_unit_code} - {item.unit_name}</Text>
      </Table.Td>
      {(can("edit master sub unit") || can("archive master sub unit") || can("restore master sub unit")) && (
        <Table.Td w={100}>
          <TableRowActions
            item={item}
            editRoute="settings.master-sub-units.edit"
            editPermission="edit master sub unit"
            archivePermission="archive master sub unit"
            restorePermission="restore master sub unit"
            archive={{
              route: "settings.master-sub-units.destroy",
              title: "Arsipkan sub unit",
              content: "Yakin ingin mengarsipkan sub unit ini?",
              confirmLabel: "Arsipkan",
            }}
            restore={{
              route: "settings.master-sub-units.restore",
              title: "Kembalikan sub unit",
              content: "Yakin ingin mengembalikan sub unit ini?",
              confirmLabel: "Kembalikan",
            }}
          />
        </Table.Td>
      )}
    </Table.Tr>
  );
}
