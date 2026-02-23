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


// ─── Sub-component: Form komentar baru ───────────────────────────────────────
export default function NewCommentForm({ currentUser, onSubmit }) {
  const editorRef = useRef(null);
  const [html, setHtml] = useState("");
  const [files, setFiles] = useState([]);
  const [loading, setLoading] = useState(false);

  function handleAddFile(newFiles) {
    // FileButton multiple mengembalikan array
    setFiles((prev) => [...prev, ...(Array.isArray(newFiles) ? newFiles : [newFiles])]);
  }

  function handleRemoveFile(index) {
    setFiles((prev) => prev.filter((_, i) => i !== index));
  }

  async function handleSubmit() {
    const stripped = html.replace(/<[^>]*>/g, "").trim();
    if (!stripped) return;

    setLoading(true);
    await onSubmit({ html, files });

    // Reset
    editorRef.current?.setContent("");
    setHtml("");
    setFiles([]);
    setLoading(false);
  }

  return (
    <Paper withBorder radius="md" p="sm">
      {/* Identitas pengirim */}
      <Group gap={8} mb={10}>
        <Avatar
          size={28}
          radius="xl"
          color={stringToColor(currentUser)}
          variant="filled"
        >
          {getInitials(currentUser)}
        </Avatar>
        <Text size="sm" fw={500}>
          {currentUser}
        </Text>
        <Text size="xs" c="dimmed">
          (Anda)
        </Text>
      </Group>

      {/* Rich text editor */}
      <RichTextEditor
        ref={editorRef}
        content={html}
        onChange={setHtml}
        placeholder="Tulis komentar... Gunakan @ untuk mention"
        height={120}
      />

      {/* Upload zone */}
      

      {/* Tombol kirim */}
      
    </Paper>
  );
}
