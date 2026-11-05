import TableRowActions from "@/components/TableRowActions";
import { Badge, Table, Text } from "@mantine/core";

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
        <Text fz="sm">{item.code_parent ? `${item.code_parent} - ${item.parent_name ?? ""}` : "-"}</Text>
      </Table.Td>
      <Table.Td>
        <Text fz="sm">{item.unit_name ?? "-"}</Text>
      </Table.Td>
      <Table.Td>
        <Text fz="sm">{item.sub_unit_name ?? "-"}</Text>
      </Table.Td>
      <Table.Td>
        <Text fz="sm">{item.jabatan_name ?? "-"}</Text>
      </Table.Td>
      <Table.Td>
        <Text fz="sm">{item.level}</Text>
      </Table.Td>
      <Table.Td>
        {item.is_pimpinan ? <Badge color="blue">Pimpinan</Badge> : <Text fz="sm">-</Text>}
      </Table.Td>
      {(can("edit master sto") || can("archive master sto") || can("restore master sto")) && (
        <Table.Td w={100}>
          <TableRowActions
            item={item}
            editRoute="settings.master-stos.edit"
            editPermission="edit master sto"
            archivePermission="archive master sto"
            restorePermission="restore master sto"
            archive={{
              route: "settings.master-stos.destroy",
              title: "Arsipkan struktur organisasi",
              content: "Yakin ingin mengarsipkan struktur organisasi ini?",
              confirmLabel: "Arsipkan",
            }}
            restore={{
              route: "settings.master-stos.restore",
              title: "Kembalikan struktur organisasi",
              content: "Yakin ingin mengembalikan struktur organisasi ini?",
              confirmLabel: "Kembalikan",
            }}
          />
        </Table.Td>
      )}
    </Table.Tr>
  );
}
