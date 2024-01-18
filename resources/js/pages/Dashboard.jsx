import { LegacyCard, Page, Text } from '@shopify/polaris';
import { useEffect } from 'react';
import { useShop } from '../providers/ShopProvider';

export default function Dashboard() {
  const { info } = useShop();

  useEffect(() => {
    window.shop = info.name;
  }, [info.name]);
  return (
    <Page narrowWidth>
      <LegacyCard title="Welcome to Integration Shopify App">
        <LegacyCard.Section>
          <Text tone="success">Here we go!</Text>
        </LegacyCard.Section>
      </LegacyCard>
    </Page>
  );
}
