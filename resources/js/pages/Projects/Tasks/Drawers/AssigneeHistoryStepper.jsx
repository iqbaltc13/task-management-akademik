import { Stepper } from '@mantine/core';
import { date } from '@/utils/datetime';

export default function AssigneeHistoryStepper({ logs = [] }) {
  if (!logs.length) return null;

  return (
    <Stepper active={logs.length} orientation="vertical" size="sm" iconSize={26} mt="md">
      {logs.map((log) => (
        <Stepper.Step
          key={log.id}
          label={log.new_assigned_user?.name || '(tidak diketahui)'}
          description={date(log.created_at)}
        />
      ))}
    </Stepper>
  );
}