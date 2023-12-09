import {
  Button,
  ButtonGroup,
  Card,
  Icon,
  IndexTable,
  Page,
  Text,
  useIndexResourceState
} from '@shopify/polaris';
import { useDeleteFilesMutation, useFilesQuery } from '../api';
import { useCallback, useState } from 'react';
import AppSpinner from '../components/AppSpinner';
import useModal from '../hooks/useModal';
import UploadModal from '../components/UploadModal';
import { CircleDownMajor, DeleteMinor } from '@shopify/polaris-icons';
import { useToast } from '../hooks/useToast';

export default function Files() {
  const [files, setFiles] = useState([]);
  const { open, toggle } = useModal();
  const { showToast } = useToast();

  const { isFetching, refetch } = useFilesQuery({
    onSuccess: (data) => {
      setFiles(data);
    }
  });
  const [id, setId] = useState(null);

  const { isLoading, mutate } = useDeleteFilesMutation(id, {
    onSuccess: () => {
      showToast({
        error: false,
        message: 'Success'
      });
      refetch();
    },
    onError: () => {
      showToast({
        error: true,
        message: 'Failed'
      });
    }
  });

  const resourceName = {
    singular: 'file',
    plural: 'files'
  };

  const {
    selectedResources,
    allResourcesSelected,
    handleSelectionChange
  } = useIndexResourceState(files);

  const handleUploadFile = useCallback(() => {
    toggle();
  }, [toggle]);

  const handleRemoveFile = useCallback(
    (id) => {
      setId(id);
      mutate(id);
    },
    [mutate]
  );

  const rowMarkup = files.map(({ id, name, type, url }) => (
    <IndexTable.Row
      id={id}
      key={id}
      selected={selectedResources.includes(id)}
    >
      <IndexTable.Cell>
        <Text variant="bodyMd" fontWeight="bold" as="span">
          {name}
        </Text>
      </IndexTable.Cell>
      <IndexTable.Cell>{type}</IndexTable.Cell>
      <IndexTable.Cell>
        <ButtonGroup>
          <Button url={url}>
            <Icon source={CircleDownMajor} />
          </Button>
          <Button
            tone="critical"
            onClick={() => {
              handleRemoveFile(id);
            }}
            loading={isLoading}
          >
            <Icon source={DeleteMinor} />
          </Button>
        </ButtonGroup>
      </IndexTable.Cell>
    </IndexTable.Row>
  ));

  if (isFetching) {
    return (
      <Page fullWidth>
        <AppSpinner />
      </Page>
    );
  }

  return (
    <Page
      title="Manage Files"
      primaryAction={{
        content: 'Upload file',
        onAction: handleUploadFile
      }}
    >
      <UploadModal open={open} onClose={toggle} onRefetch={refetch} />
      <Card>
        <IndexTable
          resourceName={resourceName}
          itemCount={files.length}
          selectedItemsCount={
            allResourcesSelected ? 'All' : selectedResources.length
          }
          onSelectionChange={handleSelectionChange}
          headings={[
            { title: 'Name' },
            { title: 'Type' },
            { title: 'Action' }
          ]}
          selectable={false}
        >
          {rowMarkup}
        </IndexTable>
      </Card>
    </Page>
  );
}
