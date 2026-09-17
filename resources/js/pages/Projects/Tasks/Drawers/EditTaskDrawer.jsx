import Dropzone from '@/components/Dropzone';
import RichTextEditor from '@/components/RichTextEditor';
import useTaskDrawerStore from '@/hooks/store/useTaskDrawerStore';
import useTasksStore from '@/hooks/store/useTasksStore';
import useWebSockets from '@/hooks/useWebSockets';
import { date } from '@/utils/datetime';
import { hasRoles } from '@/utils/user';
import { usePage, router } from '@inertiajs/react';
import RichTextEditorWithCreator from '@/components/RichTextEditorWithCreator';
import {
  Breadcrumbs,
  Button,
  Checkbox,
  Drawer,
  Flex,
  Group,
  MultiSelect,
  NumberInput,
  Select,
  Text,
  TextInput,
  rem,
} from '@mantine/core';
import { DateInput } from '@mantine/dates';
import dayjs from 'dayjs';
import { useEffect, useRef, useState } from 'react';
import Comments from './Comments';
import LabelsDropdown from './LabelsDropdown';
import Timer from './Timer';
import classes from './css/TaskDrawer.module.css';
import { PricingType } from '@/utils/enums';

export function EditTaskDrawer() {
  const editorRef = useRef(null);
  const feedbackEditorRef = useRef(null);
  const originalDataRef = useRef(null);
  const [submitting, setSubmitting] = useState(false);
  const { edit, openEditTask, closeEditTask } = useTaskDrawerStore();
  const { initTaskWebSocket } = useWebSockets();
  const { findTask, updateTaskProperty, complete, deleteAttachment, uploadAttachments } =
    useTasksStore();
  const {
    usersWithAccessToProject,
    taskGroups,
    labels,
    openedTask,
    currency,
    auth: { user },
  } = usePage().props;
  const { jobTitles } = usePage().props;
  
  useEffect(() => {
    if (openedTask) setTimeout(() => openEditTask(openedTask), 50);
  }, []);

  const task = findTask(edit.task.id);

  const [data, setData] = useState({
    code: '',
    group_id: '',
    assigned_to_user_id: '',
    name: '',
    email: '',
    identity_number: '',
    job_title: '',
    description: '',
    final_feedback: '',
    link_file_requirement: '',
    link_file_result: '',
    pricing_type: PricingType.HOURLY,
    estimation: 0,
    fixed_price: 0,
    due_on: '',
    hidden_from_clients: false,
    billable: true,
    subscribed_users: [],
    labels: [],
  });

  useEffect(() => {
    if (edit.opened) {
      return initTaskWebSocket(task);
    }
  }, [edit.opened]);

  useEffect(() => {
    if (edit.opened) {
      const initialData = {
        code: task?.code || '',
        group_id: task?.group_id || '',
        assigned_to_user_id: task?.assigned_to_user_id || '',
        name: task?.name || '',
        email: task?.email || '',
        identity_number: task?.identity_number || '', 
        job_title: task?.job_title || '',
        description: task?.description || '',
        final_feedback: task?.final_feedback || '',
        link_file_requirement: task?.link_file_requirement || '',
        link_file_result: task?.link_file_result || '',
        pricing_type: task?.pricing_type || PricingType.HOURLY,
        estimation: task?.estimation || 0,
        fixed_price: task?.fixed_price ? task.fixed_price / 100 : 0,
        due_on: task?.due_on ? dayjs(task?.due_on).toDate() : '',
        hidden_from_clients:
          task?.hidden_from_clients !== undefined ? task.hidden_from_clients : false,
        billable: task?.billable !== undefined ? task.billable : true,
        subscribed_users: (task?.subscribed_users || []).map(i => i.id.toString()),
        labels: (task?.labels || []).map(i => i.id),
      };
      setData(initialData);
      originalDataRef.current = initialData;
      setTimeout(() => {
        editorRef.current?.setContent(task?.description || '');
        feedbackEditorRef.current?.setContent(task?.final_feedback || '');
      }, 300);
    }
  }, [edit.opened, task]);

  const updateValue = (field, value) => {
    setData(prev => ({ ...prev, [field]: value }));
  };

  const isDueOnEqual = (a, b) => {
    const aStr = a ? dayjs(a).format('YYYY-MM-DD') : '';
    const bStr = b ? dayjs(b).format('YYYY-MM-DD') : '';
    return aStr === bStr;
  };

  const EDITABLE_FIELDS = [
    'group_id',
    'assigned_to_user_id',
    'name',
    'email',
    'identity_number',
    'job_title',
    'description',
    'final_feedback',
    'link_file_requirement',
    'link_file_result',
    'due_on',
    'labels',
  ];

  const handleSubmit = async event => {
    event.preventDefault();

    if (!can('edit task') || submitting) return;

    const original = originalDataRef.current || {};

    const changedFields = EDITABLE_FIELDS.filter(field => {
      if (field === 'due_on') return !isDueOnEqual(data.due_on, original.due_on);
      if (field === 'labels') return JSON.stringify(data.labels) !== JSON.stringify(original.labels);
      return data[field] !== original[field];
    });

    if (changedFields.length === 0) {
      closeEditTask();
      return;
    }

    setSubmitting(true);
    try {
      for (const field of changedFields) {
        const options = field === 'labels'
          ? data.labels.map(id => labels.find(i => i.id === id))
          : null;

        const value = field === 'due_on'
          ? (data.due_on ? dayjs(data.due_on).format('YYYY-MM-DD') : null)
          : data[field];

        await updateTaskProperty(task, field, value, options);
      }

      router.visit(route('projects.tasks', task.project_id));
    } finally {
      setSubmitting(false);
    }
  };

  const pricingTypes = [
    { value: PricingType.HOURLY, label: 'Hourly' },
    { value: PricingType.FIXED, label: 'Fixed' },
  ];

  const isFixedPrice = data.pricing_type === PricingType.FIXED;
  const currencySymbol = currency?.symbol || '';

  return (
    <Drawer
      opened={edit.opened}
      onClose={closeEditTask}
      title={
        <Group
          ml={25}
          my='sm'
          wrap='nowrap'
        >
          <Checkbox
            size='md'
            radius='xl'
            color='green'
            checked={task?.completed_at !== null}
            onChange={e => complete(task, e.currentTarget.checked)}
            className={can('complete task') ? classes.checkbox : classes.disabledCheckbox}
          />
          <Text
            fz={rem(27)}
            fw={600}
            lh={1.2}
            td={task?.completed_at !== null ? 'line-through' : null}
          >
            #{task?.number}: {data.name}
          </Text>
        </Group>
      }
      position='right'
      size={1000}
      overlayProps={{ backgroundOpacity: 0.55, blur: 3 }}
      transitionProps={{
        transition: 'slide-left',
        duration: 400,
        timingFunction: 'ease',
      }}
    >
      {task ? (
        <>
          <Breadcrumbs
            c='dark.3'
            ml={24}
            mb='xs'
            separator='I'
            separatorMargin='sm'
            styles={{ separator: { opacity: 0.3 } }}
          >
            <Text size='xs'>{task.project.name}</Text>
            <Text size='xs'>Pelayanan #{task.number}</Text>
            <Text size='xs'>
              Diterima oleh {task.created_by_user.name} pada {date(task.created_at)}
            </Text>
          </Breadcrumbs>
          <form className={classes.inner} onSubmit={handleSubmit}>
            <div className={classes.content}>
               <TextInput
                label='Kode Pelayanan'
                value={data.code}
                readOnly
              />

              <TextInput
                label='Penerima Pelayanan'
                readOnly
                mt='xl'
                value={task.created_by_user?.name || ''}
              />
              <TextInput
                label='Nama Pemohon'
                placeholder='Nama Pemohon'
                value={data.name}
                onChange={e => updateValue('name', e.target.value)}
                error={data.name.length === 0}
                readOnly={!can('edit task')}
              />

              <TextInput
                label='Email Pemohon'
                placeholder='Email Pemohon'
                type='email'
                mt='xl'
                value={data.email}
                onChange={e => updateValue('email', e.target.value)}
                readOnly={!can('edit task')}
              />
              <Select
                label='Pemohon Sebagai'
              placeholder='Pilih pemohon sebagai'
                mt="md"
                searchable
                clearable
                value={data.job_title}
                onChange={value => updateValue('job_title', value)}
                data={jobTitles.map(job => ({
                  value: job.code, // 🔑 HARUS code
                  label: job.name,
                }))}
                readOnly={!can('edit task')}
              />

              <TextInput
                label='NIM / Nomor Identitas lain'
                placeholder='NIM / Nomor Identitas lain Pemohon'
                mt="xl"
                value={data.identity_number}
                onChange={e => updateValue('identity_number', e.target.value)}
                error={!data.identity_number}
                readOnly={!can('edit task')}
              />

              

              <RichTextEditorWithCreator
                ref={editorRef}
                mt='xl'
                placeholder='Deskripsi pelayanan'
                content={data.description}
                height={260}
                onChange={content => updateValue('description', content)}
                readOnly={!can('edit task')}
              />
              <Text
                fz='sm'
                fw={500}
                mt='xl'
              >
                Feedback Akhir Pelayanan
              </Text>
              <RichTextEditorWithCreator
                ref={feedbackEditorRef}
                placeholder='Feedback Akhir Pelayanan'
                content={data.final_feedback}
                height={260}
                onChange={content => updateValue('final_feedback', content)}
                readOnly={!can('edit task')}
              />
 
              <TextInput
                label='Link File Kebutuhan Pelayanan'
                placeholder='Link File Kebutuhan Pelayanan'
                mt='xl'
                value={data.link_file_requirement}
                onChange={e => updateValue('link_file_requirement', e.target.value)}
                readOnly={!can('edit task')}
              />
 
              <TextInput
                label='Link File Hasil Pelayanan'
                placeholder='Link File Hasil Pelayanan'
                mt='xl'
                value={data.link_file_result}
                onChange={e => updateValue('link_file_result', e.target.value)}
                readOnly={!can('edit task')}
              />

              {/* {can('edit task') && (
                <Dropzone
                  mt='xl'
                  selected={task.attachments}
                  onChange={files => uploadAttachments(task, files)}
                  remove={index => deleteAttachment(task, index)}
                />
              )} */}

              {can('view comments') && <Comments task={task} />}

              <Flex justify='space-between' mt='xl'>
                <Button
                  type='button'
                  variant='transparent'
                  w={100}
                  disabled={submitting}
                  onClick={closeEditTask}
                >
                  Batal
                </Button>

                {can('edit task') && (
                  <Button
                    type='submit'
                    w={170}
                    loading={submitting}
                  >
                    Update Pelayanan
                  </Button>
                )}
              </Flex>
            </div>
            <div className={classes.sidebar}>
              <Select
                label='Status Pelayanan'
                placeholder='Pilih Status Pelayanan'
                allowDeselect={false}
                value={data.group_id.toString()}
                onChange={value => updateValue('group_id', value)}
                data={taskGroups.map(i => ({
                  value: i.id.toString(),
                  label: i.name,
                }))}
                readOnly={!can('edit task')}
              />

              <Select
                label='Penerima Tugas'
                placeholder='Pilih penerima tugas'
                searchable
                mt='md'
                value={data.assigned_to_user_id?.toString()}
                onChange={value => updateValue('assigned_to_user_id', value)}
                data={usersWithAccessToProject.map(i => ({
                  value: i.id.toString(),
                  label: i.name,
                }))}
                readOnly={!can('edit task')}
              />

              <DateInput
                clearable
                locale="id"
                valueFormat='DD MMM YYYY'
                minDate={new Date()}
                mt='md'
                label='Batas Waktu'
                placeholder='Pilih batas waktu'
                value={data.due_on}
                onChange={value => updateValue('due_on', value)}
                readOnly={!can('edit task')}
              />

              <LabelsDropdown
                items={labels}
                selected={data.labels}
                onChange={values => updateValue('labels', values)}
                mt='md'
              />

              {/* <NumberInput
                label='Estimasi Waktu'
                mt='md'
                decimalScale={2}
                fixedDecimalScale
                value={data.estimation}
                min={0}
                allowNegative={false}
                step={0.5}
                suffix=' jam'
                onChange={value => updateValue('estimation', value)}
                readOnly={!can('edit task')}
              /> */}

              {/* <Select
                label='Pricing type'
                placeholder='Select pricing type'
                mt='md'
                value={data.pricing_type}
                onChange={value => updateValue('pricing_type', value)}
                data={pricingTypes}
                readOnly={!can('edit task')}
              /> */}

              {/* {isFixedPrice && (can('view time logs') || can('add time log')) && (
                <NumberInput
                  label='Fixed price'
                  mt='md'
                  decimalScale={2}
                  fixedDecimalScale
                  value={data.fixed_price}
                  min={0}
                  allowNegative={false}
                  onChange={value => updateValue('fixed_price', value)}
                  prefix={currencySymbol}
                  readOnly={!can('edit task')}
                />
              )} */}

              {/* {!isFixedPrice && (can('view time logs') || can('add time log')) && (
                <Timer
                  mt='xl'
                  task={task}
                />
              )} */}

              {/* <Checkbox
                label='Billable'
                mt='xl'
                checked={data.billable}
                onChange={event => updateValue('billable', event.currentTarget.checked)}
                disabled={!can('edit task')}
              /> */}

              {/* {!hasRoles(user, ['client']) && (
                <Checkbox
                  label='Disembunyikan dari klien'
                  mt='md'
                  checked={data.hidden_from_clients}
                  onChange={event =>
                    updateValue('hidden_from_clients', event.currentTarget.checked)
                  }
                  disabled={!can('edit task')}
                />
              )} */}

              <MultiSelect
                label='Penerima Notifikasi'
                placeholder={!data.subscribed_users.length ? 'Pilih penerima notifikasi' : null}
                mt='lg'
                value={data.subscribed_users}
                onChange={values => updateValue('subscribed_users', values)}
                data={usersWithAccessToProject.map(i => ({
                  value: i.id.toString(),
                  label: i.name,
                }))}
                // readOnly={!can('edit task')}
                readOnly={true}
              />
            </div>
          </form>
        </>
      ) : (
        <></>
      )}
    </Drawer>
  );
}