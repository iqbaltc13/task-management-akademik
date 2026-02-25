import RichTextEditor from "@/components/RichTextEditor";
import useTasksStore from "@/hooks/store/useTasksStore";
import { dateTime, diffForHumans } from "@/utils/datetime";
import {
  Avatar,
  Box,
  Button,
  Center,
  Flex,
  Group,
  Loader,
  Stack,
  Text,
  Title,
  Tooltip,
} from "@mantine/core";
import { useEffect, useRef, useState } from "react";
import classes from "./css/Comments.module.css";

export default function CommentOnCreate() {
  const { comments, fetchComments, saveComment } = useTasksStore();
  const [loading, setLoading] = useState(true);
  const [comment, setComment] = useState("");
  const editorRef = useRef(null);

  useEffect(() => {
    fetchComments(task, () => setLoading(false));
  }, []);

  return (
    <Box mb="xl">
      <Title order={3} mt="xl">
        {/* Discussion */}
        {!loading && (
          <Text c="dimmed" fw={500} display="inline-block" ml={5}>
            ({comments.length})
          </Text>
        )}
      </Title>
      <RichTextEditor
        ref={editorRef}
        mt="md"
        placeholder="Tulis komentar"
        height={200}
        content={comment}
        onChange={(content) => setComment(content)}
      />
      <Flex justify="flex-end">
        <Button
          variant="filled"
          mt="md"
          disabled={comment.length <= 7}
          onClick={() => saveComment(task, comment, () => editorRef.current.setContent(""))}
        >
          Add comment
        </Button>
      </Flex>

    </Box>
  );
}
