import { useRef, useState } from "react";
import {
  Avatar,
  Box,
  Button,
  Divider,
  FileButton,
  Group,
  Paper,
  Stack,
  Text,
  ThemeIcon,
  Timeline,
  Tooltip,
} from "@mantine/core";
import { IconPaperclip, IconSend, IconTrash, IconX } from "@tabler/icons-react";
import RichTextEditor from "./RichTextEditor"; // sesuaikan path

// ─── Helper: warna avatar dari nama ──────────────────────────────────────────
function stringToColor(name = "") {
  const colors = [
    "violet", "indigo", "blue", "cyan", "teal",
    "green", "orange", "pink", "grape", "red",
  ];
  let hash = 0;
  for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
  return colors[Math.abs(hash) % colors.length];
}

function getInitials(name = "") {
  return name
    .split(" ")
    .map((w) => w[0])
    .join("")
    .toUpperCase()
    .slice(0, 2);
}

// ─── Sub-component: Upload Zone ───────────────────────────────────────────────
export default function UploadZone({ files, onAdd, onRemove }) {
  return (
    <Box>
      <FileButton onChange={(f) => f && onAdd(f)} multiple>
        {(props) => (
          <Button
            {...props}
            variant="default"
            size="xs"
            leftSection={<IconPaperclip size={14} />}
          >
            Lampirkan File
          </Button>
        )}
      </FileButton>

      {files.length > 0 && (
        <Group gap={6} mt={8} wrap="wrap">
          {files.map((file, i) => (
            <Paper
              key={i}
              withBorder
              px={10}
              py={4}
              radius="xl"
              style={{ display: "flex", alignItems: "center", gap: 6 }}
            >
              <IconPaperclip size={12} style={{ opacity: 0.5 }} />
              <Text size="xs" c="dimmed" lineClamp={1} maw={160}>
                {file.name}
              </Text>
              <Tooltip label="Hapus">
                <ThemeIcon
                  variant="transparent"
                  color="red"
                  size="xs"
                  style={{ cursor: "pointer" }}
                  onClick={() => onRemove(i)}
                >
                  <IconX size={11} />
                </ThemeIcon>
              </Tooltip>
            </Paper>
          ))}
        </Group>
      )}
    </Box>
  );
}