import TableRowActions from "@/components/TableRowActions";
import { Table, Text } from "@mantine/core";

export default function TableRow({ item }) {
  return (
    <Table.Tr key={item.id}>
      <Table.Td w={140}>
        <Text fz="sm" fw={500}>{item.code}</Text>
      </Table.Td>
      <Table.Td>
        <Text fz="sm">{item.name}</Text>
      </Table.Td>
      <Table.Td w={140}>
        {item.jumlah_hari !== null && item.jumlah_hari !== undefined ? (
          <Text fz="sm">{item.jumlah_hari} hari</Text>
        ) : (
          <Text fz="sm" c="dimmed">Dipilih manual</Text>
        )}
      </Table.Td>
      <Table.Td>
        <Text fz="sm" c="dimmed" lineClamp={2}>{item.keterangan || "-"}</Text>
      </Table.Td>
      {(can("edit jenis pelayanan") || can("archive jenis pelayanan") || can("restore jenis pelayanan")) && (
        <Table.Td w={100}>
          <TableRowActions
            item={item}
            editRoute="settings.jenis-pelayanans.edit"
            editPermission="edit jenis pelayanan"
            archivePermission="archive jenis pelayanan"
            restorePermission="restore jenis pelayanan"
            archive={{
              route: "settings.jenis-pelayanans.destroy",
              title: "Arsipkan jenis pelayanan",
              content: "Apakah anda yakin mengarsipkan jenis pelayanan ini?",
              confirmLabel: "Arsipkan",
            }}
            restore={{
              route: "settings.jenis-pelayanans.restore",
              title: "Kembalikan jenis pelayanan",
              content: "Apakah anda yakin mengembalikan jenis pelayanan ini?",
              confirmLabel: "Kembalikan",
            }}
          />
        </Table.Td>
      )}
    </Table.Tr>
  );
}
