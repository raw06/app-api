import { LegacyCard, Page, Text } from '@shopify/polaris';

export default function Dashboard() {
  return (
    <Page narrowWidth>
      <LegacyCard title="Welcome to Integration Shopify App">
        <LegacyCard.Section>
          <Text tone="success">
            Here we go!
          </Text>
        </LegacyCard.Section>
      </LegacyCard>
    </Page>
  );
}
