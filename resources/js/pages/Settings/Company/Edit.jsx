import ActionButton from '@/components/ActionButton';
import useForm from '@/hooks/useForm';
import ContainerBox from '@/layouts/ContainerBox';
import Layout from '@/layouts/MainLayout';
import { usePage } from '@inertiajs/react';
import {
  Box,
  Fieldset,
  FileInput,
  Grid,
  Group,
  Image,
  NumberInput,
  Select,
  Text,
  TextInput,
  Title,
} from '@mantine/core';

const CompanyEdit = () => {
  const {
    item,
    dropdowns: { countries, currencies },
  } = usePage().props;

  const [form, submit, updateValue] = useForm('post', route('settings.company.update'), {
    _method: 'put',
    logo: null,
    name: item.name || '',
    address: item.address || '',
    postal_code: item.postal_code || '',
    city: item.city || '',
    country_id: item.country_id || '',
    currency_id: item.currency_id || '',
    email: item.email || '',
    phone: item.phone || '',
    web: item.web || '',
    iban: item.iban || '',
    swift: item.swift || '',
    business_id: item.business_id || '',
    tax_id: item.tax_id || '',
    vat: item.vat || '',
    tax: item.tax / 100 || 0,
  });

  return (
    <>
      <Grid
        justify='space-between'
        align='flex-end'
        gutter='xl'
        mb={35}
      >
        <Grid.Col span='auto'>
          <Title order={1}>My company</Title>
        </Grid.Col>
        <Grid.Col span='content'></Grid.Col>
      </Grid>

      <ContainerBox maw={600}>
        <form onSubmit={e => submit(e, { forceFormData: true })}>
          <Grid
            justify='flex-start'
            align='center'
            gutter='lg'
          >
            <Grid.Col span='content'>
              {item.logo || form.data.logo ? (
                <Image
                  src={form.data.logo === null ? item.logo : URL.createObjectURL(form.data.logo)}
                  w={240}
                  h={64}
                />
              ) : (
                <Box
                  w={240}
                  h={64}
                  bg='#25262b'
                  align='center'
                  pt='lg'
                  opacity={0.6}
                >
                  Company logo
                </Box>
              )}
            </Grid.Col>
            <Grid.Col span='auto'>
              <FileInput
                label='Logo'
                placeholder='Choose image'
                accept='image/png,image/jpeg'
                onChange={image => updateValue('logo', image)}
                clearable
                error={form.errors.logo}
                disabled={!can('edit owner company')}
              />
              <Text
                size='xs'
                c='dimmed'
                mt='sm'
              >
                240px &times; 64px (aspect 15:4)
              </Text>
            </Grid.Col>
          </Grid>

          <TextInput
            label='Name'
            placeholder='Company name'
            required
            mt='md'
            value={form.data.name}
            onChange={e => updateValue('name', e.target.value)}
            error={form.errors.name}
            disabled={!can('edit owner company')}
          />

          <Fieldset
            legend='Location'
            mt='xl'
          >
            <TextInput
              label='Address'
              placeholder='Address'
              value={form.data.address}
              onChange={e => updateValue('address', e.target.value)}
              error={form.errors.address}
              disabled={!can('edit owner company')}
            />

            <Group grow>
              <TextInput
                label='Postal code'
                placeholder='Postal code'
                mt='md'
                value={form.data.postal_code}
                onChange={e => updateValue('postal_code', e.target.value)}
                error={form.errors.postal_code}
                disabled={!can('edit owner company')}
              />

              <TextInput
                label='City'
                placeholder='City'
                mt='md'
                value={form.data.city}
                onChange={e => updateValue('city', e.target.value)}
                error={form.errors.city}
                disabled={!can('edit owner company')}
              />
            </Group>

            <Select
              label='Country'
              placeholder='Select country'
              mt='md'
              searchable={true}
              value={form.data.country_id?.toString()}
              onChange={value => updateValue('country_id', value)}
              data={countries}
              error={form.errors.country_id}
              disabled={!can('edit owner company')}
            />
          </Fieldset>

          

          

          <Fieldset
            legend='Contact'
            mt='xl'
          >
            <Group grow>
              <TextInput
                label='Email'
                placeholder='Email'
                value={form.data.email}
                onChange={e => updateValue('email', e.target.value)}
                error={form.errors.email}
                disabled={!can('edit owner company')}
              />

              <TextInput
                label='Phone'
                placeholder='Phone'
                value={form.data.phone}
                onChange={e => updateValue('phone', e.target.value)}
                error={form.errors.phone}
                disabled={!can('edit owner company')}
              />
            </Group>

            <TextInput
              label='Web'
              placeholder='Web'
              mt='md'
              value={form.data.web}
              onChange={e => updateValue('web', e.target.value)}
              error={form.errors.web}
              disabled={!can('edit owner company')}
            />
          </Fieldset>

          <Group
            justify='flex-end'
            mt='xl'
          >
            {can('edit owner company') && (
              <ActionButton loading={form.processing}>Save</ActionButton>
            )}
          </Group>
        </form>
      </ContainerBox>
    </>
  );
};

CompanyEdit.layout = page => <Layout title='Edit user'>{page}</Layout>;

export default CompanyEdit;
