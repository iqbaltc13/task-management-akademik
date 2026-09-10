import ActionButton from '@/components/ActionButton';
import BackButton from '@/components/BackButton';
import useForm from '@/hooks/useForm';
import ContainerBox from '@/layouts/ContainerBox';
import Layout from '@/layouts/MainLayout';
import { redirectTo } from '@/utils/route';
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


const ProjectCreate = ({ dropdowns: { companies, users, currencies } }) => {
  const [currencySymbol, setCurrencySymbol] = useState();

  
  const [form, submit, updateValue] = useForm('post', route('projects.store'), {
    name: '',
    start_date: dayjs().startOf('year').format('YYYY-MM-DD'),
    end_date: dayjs().endOf('year').format('YYYY-MM-DD'),
    description: '',
    default_pricing_type: PricingType.HOURLY,
    rate: 0,
    client_company_id: '',
    users: [],
  });
  
  
  const pricingTypes = [
    { value: PricingType.HOURLY, label: 'Hourly' },
    { value: PricingType.FIXED, label: 'Fixed' },
  ];

  const [dateRange, setDateRange] = useState([
    dayjs().startOf('year').toDate(),
    dayjs().endOf('year').toDate()
  ]);

  const handleDateChange = (value) => {
    setDateRange(value);
    
    const startOfYear = dayjs().startOf('year').format('YYYY-MM-DD');
    const endOfYear = dayjs().endOf('year').format('YYYY-MM-DD');
    
    updateValue('start_date', value[0] ? dayjs(value[0]).format('YYYY-MM-DD') : startOfYear);
    updateValue('end_date', value[1] ? dayjs(value[1]).format('YYYY-MM-DD') : endOfYear);
  };
  
  useEffect(() => {
    let symbol = currencies.find(i =>
      i.client_companies.find(c => c.id.toString() === form.data.client_company_id)
    )?.symbol;

    if (symbol) {
      setCurrencySymbol(symbol);
    }
  }, [form.data.client_company_id]);

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
          Periode Pelayanan
        </Anchor>
        <div>Tambah</div>
      </Breadcrumbs>

      <Grid
        justify='space-between'
        align='flex-end'
        gutter='xl'
        mb='lg'
      >
        <Grid.Col span='auto'>
          <Title order={1}>Tambah Periode Pelayanan</Title>
        </Grid.Col>
        <Grid.Col span='content'></Grid.Col>
      </Grid>

      <ContainerBox maw={500}>
        <form onSubmit={submit}>
          <TextInput
            label='Nama'
            placeholder='Nama Periode Pelayanan'
            required
            mt='md'
            value={form.data.name}
            onChange={e => updateValue('name', e.target.value)}
            error={form.errors.name}
          />

          <Textarea
            label='Deskripsi'
            placeholder='Deskripsi Periode Pelayanan'
            mt='md'
            autosize
            minRows={4}
            maxRows={8}
            value={form.data.description}
            onChange={e => updateValue('description', e.target.value)}
          />

          {/* <Select
            label='Institusi'
            placeholder='Pilih institusi'
            required
            mt='md'
            value={form.data.client_company_id}
            onChange={value => updateValue('client_company_id', value)}
            data={companies}
            error={form.errors.client_company_id}
          /> */}

         <DatesProvider settings={{ timezone: "utc" }}>
            <DatePickerInput
              label="Periode Tanggal"
              type="range"
              mt='md'
              valueFormat="YYYY-MM-DD"
              placeholder="Pick dates range"
              clearable
              allowSingleDateInRange
              miw={200}
              value={dateRange}
              onChange={handleDateChange}

            />
          </DatesProvider>

          {/* <MultiSelect
            label='Berikan akses kepada pengguna'
            placeholder='Pilih Pengguna'
            mt='md'
            searchable
            value={form.data.users}
            onChange={values => updateValue('users', values)}
            data={users}
            error={form.errors.users}
          /> */}

          

          

          <Group
            justify='space-between'
            mt='xl'
          >
            <BackButton route='projects.index' />
            <ActionButton loading={form.processing}>Tambah</ActionButton>
          </Group>
        </form>
      </ContainerBox>
    </>
  );
};

ProjectCreate.layout = page => <Layout title='Create project'>{page}</Layout>;

export default ProjectCreate;
