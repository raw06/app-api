import { useCallback, useState } from 'react';

// eslint-disable-next-line no-unused-vars
const useModal = () => {
  const [open, setOpen] = useState(false);

  const toggle = useCallback(() => {
    setOpen((value) => !value);
  }, []);

  return {
    open,
    toggle,
    setOpen
  };
};
export default useModal;
