import { Grid, Page } from '@shopify/polaris';
import { usePartnerQuery } from '../api';
import { useState } from 'react';
import AppSpinner from '../components/AppSpinner';
import IntegrationCard from '../components/IntegrationCard';

export default function Integration() {
  const [partners, setPartners] = useState([]);

  const { isFetching } = usePartnerQuery({
    onSuccess: (data) => {
      setPartners(data);
    }
  });

  if (isFetching) {
    return (
      <Page fullWidth>
        <AppSpinner />
      </Page>
    );
  }
  return (
    <Page title="Integrations">
      <Grid>
        {partners.map((partner) => (
          <IntegrationCard key={partner.id} partner={partner} />
        ))}
      </Grid>
    </Page>
  );
}
