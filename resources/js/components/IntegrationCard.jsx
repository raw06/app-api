import {
  BlockStack,
  Button,
  Card,
  Grid,
  InlineStack,
  LegacyStack,
  Text,
  Thumbnail
} from '@shopify/polaris';
import PropTypes from 'prop-types';
import { useCallback } from 'react';

export default function IntegrationCard({ partner }) {
  const handleRedirect = useCallback((link) => {
    window.open(link);
  }, []);

  return (
    <Grid.Cell columnSpan={{ xs: 6, sm: 3, md: 3, lg: 6, xl: 6 }}>
      <Card>
        <LegacyStack wrap={false}>
          <Thumbnail
            size="small"
            source={partner.app_logo}
            alt={partner.name}
          />
          <LegacyStack.Item fill>
            <BlockStack gap={100}>
              <InlineStack blockAlign="center" align="space-between">
                <Text variant="headingMd">{partner.name}</Text>
                <Button
                  size="micro"
                  onClick={() => {
                    handleRedirect(partner.app_link);
                  }}
                  disabled={!partner.app_link}
                >
                  Connect
                </Button>
              </InlineStack>
              <Text tone="subdued" variant="bodySm" truncate>
                {partner.description}
              </Text>
            </BlockStack>
          </LegacyStack.Item>
        </LegacyStack>
      </Card>
    </Grid.Cell>
  );
}

IntegrationCard.propTypes = {
  partner: PropTypes.object.isRequired
};
