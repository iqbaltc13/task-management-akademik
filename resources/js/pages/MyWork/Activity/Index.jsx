import EmptyWithIcon from "@/components/EmptyWithIcon";
import Layout from "@/layouts/MainLayout";
import { dateTime, diffForHumans } from "@/utils/datetime";
import { redirectTo, reloadWithQuery, reloadWithoutQueryParams } from "@/utils/route";
import { usePage } from "@inertiajs/react";
import { Anchor, Breadcrumbs, Center, Select, Text, Timeline, Title, Tooltip } from "@mantine/core";
import {
  IconActivity,
  IconArchive,
  IconCalendarMonth,
  IconCheck,
  IconClock,
  IconEdit,
  IconMessage,
  IconPaperclip,
  IconPlus,
  IconX,
} from "@tabler/icons-react";
import { useEffect, useState } from "react";
import dayjs from 'dayjs';
import 'dayjs/locale/id';
dayjs.locale('id');

const ActivityIndex = () => {
  let { groupedActivities, dropdowns } = usePage().props;

  const [selectedProject, setSelectedProject] = useState(route().params?.project || "0");

  useEffect(() => {
    if (selectedProject > 0) {
      reloadWithQuery({ project: selectedProject });
    } else {
      reloadWithoutQueryParams({ exclude: ["project"] });
    }
  }, [selectedProject]);

  const getIcon = (title) => {
    if (title.includes("dihapus")) {
      return <IconArchive size={18} />;
    }
    if (title.includes("komentar")) {
      return <IconMessage size={18} />;
    }
    if (title.includes("diperbarui")) {
      return <IconEdit size={18} />;
    }
    if (title.includes("Batas waktu")) {
      return <IconCalendarMonth size={18} />;
    }
    if (title.includes("Lampiran")) {
      return <IconPaperclip size={18} />;
    }
    if (title.includes("Estimasi telah diatur")) {
      return <IconClock size={18} />;
    }
    if (title.includes("telah selesai")) {
      return <IconCheck size={18} />;
    }
    if (title.includes("belum selesai")) {
      return <IconX size={18} />;
    }
    if (title === "Pelayanan baru" || title === "Grup Pelayanan baru" || title.includes("Pengguna ditugaskan")) {
      return <IconPlus size={18} />;
    }
  };

  return (
    <>
      <Breadcrumbs fz={14} mb={30}>
        <div>Pekerjaan Saya</div>
        <div> Aktivitas Pelayanan</div>
      </Breadcrumbs>

      <Title order={1} mb={20}>
        Aktivitas Pelayanan
      </Title>

      <Select
        size="md"
        placeholder="Pilih pelayanan"
        allowDeselect={false}
        value={selectedProject}
        onChange={(value) => setSelectedProject(value)}
        data={dropdowns.projects}
        mb={35}
        maw={260}
      />

      {Object.keys(groupedActivities).length ? (
        Object.keys(groupedActivities).map((date) => (
          <div key={date}>
            <Title order={3} mb="lg">
              {dayjs(date).format('DD MMMM YYYY')}
            </Title>
            <Timeline active={9999} bulletSize={32} lineWidth={3} mb="xl">
              {groupedActivities[date].map((activity) => (
                <Timeline.Item key={activity.id} bullet={getIcon(activity.title)}>
                  <div>
                    <Anchor
                      href="#"
                      fz="md"
                      fw={600}
                      onClick={() =>
                        redirectTo("projects.tasks.open", [
                          activity.project.id,
                          activity.activity_capable.id,
                        ])
                      }
                    >
                      {activity.title}
                    </Anchor>
                    <Text c="dimmed" size="sm">
                      {activity.subtitle}
                    </Text>
                    <Anchor
                      href="#"
                      fz="xs"
                      onClick={() => redirectTo("projects.tasks", [activity.project_id])}
                    >
                      {activity.project.name}
                    </Anchor>
                  </div>
                  <Tooltip label={dateTime(activity.created_at)} openDelay={1000} withArrow>
                    <Text inline span size="xs" mt={4}>
                      {diffForHumans(activity.created_at)}
                    </Text>
                  </Tooltip>
                </Timeline.Item>
              ))}
            </Timeline>
          </div>
        ))
      ) : (
        <Center mih={300}>
          <EmptyWithIcon
            title="Tidak ada aktivitas yang ditemukan"
            subtitle="Pada pelayanan yang dapat Anda akses"
            icon={IconActivity}
          />
        </Center>
      )}
    </>
  );
};

ActivityIndex.layout = (page) => <Layout title="Activity">{page}</Layout>;

export default ActivityIndex;
