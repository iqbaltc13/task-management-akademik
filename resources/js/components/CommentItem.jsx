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



// ─── Sub-component: Satu item komentar ───────────────────────────────────────
export default function CommentItem({ comment, onDelete, currentUser }) {
  const isOwn = comment.author === currentUser;

  return (
    <Timeline.Item
      bullet={
        <Avatar
          size={32}
          radius="xl"
          color={stringToColor(comment.author)}
          variant="filled"
        >
          {getInitials(comment.author)}
        </Avatar>
      }
      // Sembunyikan default dot, ganti dengan avatar di atas
      style={{ "--tli-bullet-size": "32px" }}
    >
      <Paper withBorder radius="md" p="sm" mb={4}>
        {/* Header komentar */}
        <Group justify="space-between" mb={6}>
          <Group gap={8}>
            <Text fw={600} size="sm">
              {comment.author}
            </Text>
            <Text size="xs" c="dimmed">
              {comment.time}
            </Text>
          </Group>

          {isOwn && (
            <Tooltip label="Hapus komentar">
              <ThemeIcon
                variant="subtle"
                color="red"
                size="sm"
                style={{ cursor: "pointer" }}
                onClick={() => onDelete(comment.id)}
              >
                <IconTrash size={14} />
              </ThemeIcon>
            </Tooltip>
          )}
        </Group>

        {/* Isi komentar – RichTextEditor mode readOnly */}
        <RichTextEditor
          content={comment.html}
          onChange={() => {}}   // readOnly, tidak perlu handler
          readOnly
          height={80}           // auto-fit isi
        />

        {/* File lampiran */}
        {comment.files.length > 0 && (
          <>
            <Divider my={8} />
            <Group gap={6} wrap="wrap">
              {comment.files.map((file, i) => (
                <Paper key={i} withBorder px={10} py={4} radius="xl">
                  <Group gap={6}>
                    <IconPaperclip size={12} style={{ opacity: 0.5 }} />
                    <Text size="xs" c="dimmed">
                      {file.name}
                    </Text>
                  </Group>
                </Paper>
              ))}
            </Group>
          </>
        )}
      </Paper>

      {/* Upload zone per-komentar (tampil hanya jika komentar milik sendiri) */}
      {isOwn && (
        <Text size="xs" c="dimmed" mt={2}>
          — lampiran tidak bisa diubah setelah dikirim
        </Text>
      )}
    </Timeline.Item>
  );
}