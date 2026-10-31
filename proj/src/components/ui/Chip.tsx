interface ChipProps {
  label: string;
  count?: number;
  active: boolean;
  onClick: () => void;
}

/** Filter pill used on /berita and /panduan category rows. */
export function Chip({ label, count, active, onClick }: ChipProps) {
  return (
    <button type="button" className={active ? "sj-chip sj-active" : "sj-chip"} aria-pressed={active} onClick={onClick}>
      {label}
      {count !== undefined ? <small>{count}</small> : null}
    </button>
  );
}