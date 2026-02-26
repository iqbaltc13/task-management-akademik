import ActionButton from '@/components/ActionButton';
import BackButton from '@/components/BackButton';
import useForm from '@/hooks/useForm';
import ContainerBox from '@/layouts/ContainerBox';
import Layout from '@/layouts/MainLayout';
import { redirectTo } from '@/utils/route';
import { usePage } from '@inertiajs/react';
import { DateRangePicker } from 'react-date-range';
import {
  Anchor,
  Breadcrumbs,
  Grid,
  Group,
  MultiSelect,
  NumberInput,
  Select,
  TextInput,
  Textarea,
  Title,
} from '@mantine/core';
import { useEffect, useState } from 'react';
import { PricingType } from '@/utils/enums';
import { DatePickerInput, DatesProvider } from "@mantine/dates";
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
dayjs.extend(utc);

const ProjectEdit = ({ dropdowns: { companies, users, currencies } }) => {
  const { item } = usePage().props;
  const [currencySymbol, setCurrencySymbol] = useState();

  const [form, submit, updateValue] = useForm('post', route('projects.update', item.id), {
    _method: 'put',
    name: item.name,
    description: item.description || '',
    default_pricing_type: item.default_pricing_type || PricingType.HOURLY,
    client_company_id: item.client_company_id || '',
    start_date: item.start_date || '',
    end_date: item.end_date || '',
    rate: item.rate / 100 || 0,
    users: item.users.map(i => i.id.toString()),
  });

  const [dateRange, setDateRange] = useState([
    item.start_date ? dayjs.utc(item.start_date).toDate() : null,
    item.end_date ? dayjs.utc(item.end_date).toDate() : null,
  ]);

  

  const handleDateChange = (value) => {
    setDateRange(value);
    
    // Update form dengan format YYYY-MM-DD (sesuai format DB)
    updateValue('start_date', value[0] ? dayjs(value[0]).format('YYYY-MM-DD') : '');
    updateValue('end_date', value[1] ? dayjs(value[1]).format('YYYY-MM-DD') : '');
  };
  
  useEffect(() => {
    let symbol = currencies.find(i =>
      i.client_companies.find(c => c.id.toString() === form.data.client_company_id.toString())
    )?.symbol;

    if (symbol) {
      setCurrencySymbol(symbol);
    }
  }, [form.data.client_company_id]);

  const pricingTypes = [
    { value: PricingType.HOURLY, label: 'Hourly' },
    { value: PricingType.FIXED, label: 'Fixed' },
  ];

  return (
    <>
      <Breadcrumbs
        fz={14}
        mb={30}
      >
        <Anchor
          href='#'
          onClick={() => redirectTo('projects.index')}
          fz={14}
        >
          Periode Permintaan
        </Anchor>
        <div>Edit</div>
      </Breadcrumbs>

      <Grid
        justify='space-between'
        align='flex-end'
        gutter='xl'
        mb='lg'
      >
        <Grid.Col span='auto'>
          <Title order={1}>Edit Periode Permintaan</Title>
        </Grid.Col>
        <Grid.Col span='content'></Grid.Col>
      </Grid>

      <ContainerBox maw={500}>
        <form onSubmit={submit}>
          <TextInput
            label='Nama'
            placeholder='Nama Periode Permintaan'
            required
            mt='md'
            value={form.data.name}
            onChange={e => updateValue('name', e.target.value)}
            error={form.errors.name}
          />

          <Textarea
            label='Deskripsi'
            placeholder='Deskripsi Periode Permintaan'
            mt='md'
            autosize
            minRows={4}
            maxRows={8}
            value={form.data.description}
            onChange={e => updateValue('description', e.target.value)}
          />
          <DatesProvider settings={{ timezone: "utc" }}>
            <DatePickerInput
              label="Periode Tanggal"
              type="range"
              mt='md'
              valueFormat="YYYY-MM-DD"
              placeholder="Pilih rentang tanggal"
              locale="id"
              clearable
              allowSingleDateInRange
              miw={200}
              value={dateRange}
              onChange={handleDateChange}
            />
          </DatesProvider>

          {/* <Select
            label='Institusi'
            placeholder='Pilih institusi'
            required
            mt='md'
            value={form.data.client_company_id?.toString()}
            onChange={value => updateValue('client_company_id', value)}
            data={companies}
            error={form.errors.client_company_id}
          /> */}
         
          <MultiSelect
            label='Berikan akses kepada pengguna'
            placeholder='Pilih Pengguna'
            mt='md'
            searchable
            value={form.data.users}
            onChange={values => updateValue('users', values)}
            data={users}
            error={form.errors.users}
          />

          {/* <Select
            label='Default pricing type'
            placeholder='Select pricing type'
            required
            mt='md'
            value={form.data.default_pricing_type}
            onChange={value => updateValue('default_pricing_type', value)}
            data={pricingTypes}
            error={form.errors.default_pricing_type}
          /> */}

          {/* <NumberInput
            label='Hourly rate'
            mt='md'
            allowNegative={false}
            clampBehavior='strict'
            decimalScale={2}
            fixedDecimalScale={true}
            prefix={currencySymbol}
            value={form.data.rate}
            onChange={value => updateValue('rate', value)}
            error={form.errors.rate}
          /> */}

          <Group
            justify='space-between'
            mt='xl'
          >
            <BackButton route='projects.index' />
            <ActionButton loading={form.processing}>Simpan</ActionButton>
          </Group>
        </form>
      </ContainerBox>
    </>
  );
};

ProjectEdit.layout = page => <Layout title='Edit project'>{page}</Layout>;

export default ProjectEdit;
