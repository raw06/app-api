import {
  DropZone,
  LegacyStack,
  Modal,
  Text,
  Thumbnail
} from '@shopify/polaris';
import { useFilesMutation } from '../api';
import { useCallback, useState } from 'react';
import { NoteMinor } from '@shopify/polaris-icons';
import { useToast } from '../hooks/useToast';

// eslint-disable-next-line react/prop-types
export default function UploadModal({ open, onClose, onRefetch }) {
  const { isLoading, mutate } = useFilesMutation({
    onSuccess: () => {
      onClose();
      onRefetch();
      showToast({
        error: false,
        message: 'Success'
      });
    },
    onError: () => {
      showToast({
        error: true,
        message: 'Failed'
      });
    }
  });

  const [file, setFile] = useState(null);
  const { showToast } = useToast();

  const handleDropZoneDrop = useCallback(
    (_dropFiles, acceptedFiles) => setFile(() => acceptedFiles[0]),
    []
  );

  const handleCreateFile = useCallback(() => {
    if (!file) {
      showToast({
        error: true,
        message: 'Please upload file'
      });
      return;
    }
    const formData = new FormData();
    formData.append('file', file);
    mutate(formData);
  }, [file, mutate, showToast]);

  const fileUpload = !file && <DropZone.FileUpload />;
  const uploadedFile = file && (
    <div style={{ padding: '0' }}>
      <LegacyStack vertical>
        <LegacyStack alignment="center">
          <Thumbnail
            size="small"
            alt={file.name}
            source={NoteMinor}
          />
          <div>
            {file.name}{' '}
            <Text variant="bodySm" as="p">
              {file.size} bytes
            </Text>
          </div>
        </LegacyStack>
      </LegacyStack>
    </div>
  );
  return (
    <Modal
      title="Upload your file"
      open={open}
      onClose={onClose}
      primaryAction={{
        content: 'Create',
        onAction: handleCreateFile,
        loading: isLoading
      }}
    >
      <Modal.Section>
        <DropZone onDrop={handleDropZoneDrop}>
          {uploadedFile}
          {fileUpload}
        </DropZone>
      </Modal.Section>
    </Modal>
  );
}
