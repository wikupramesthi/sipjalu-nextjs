import { ChevronRight, MapPin } from "lucide-react";
import type { DistrictProgress, SubdistrictProgress } from "../../types";
import { buildPath } from "../../config/routes";
import { BarCell } from "../ui/ProgressBar";
import { SmartLink } from "../ui/SmartLink";

export interface DrillRow {
  kode: string;
  name: string;
  total: number;
  sent: number;
  progress: number;
}

interface RegionTableProps {
  /** Base path segment — "/progres" in this demo. */
  base: string;
  parentSegments: (string | number)[];
  rows: readonly (DrillRow | DistrictProgress | SubdistrictProgress)[];
  nextLabel: string;
  /** Set false on the deepest level (no detail link rendered). */
  drillable?: boolean;
}

/** Table linking one administrative level down (provinsi → kabupaten → kecamatan). */
export function RegionTable({ base, parentSegments, rows, nextLabel, drillable = true }: RegionTableProps) {
  const nf = new Intl.NumberFormat("id-ID");
  return (
    <div className="sj-list-panel">
      <table className="sj-data-table">
        <thead>
          <tr>
            <th scope="col">Wilayah</th>
            <th scope="col">Sekolah</th>
            <th scope="col">Terkirim</th>
            <th scope="col" className="sj-col-progress">Progres</th>
            <th scope="col" className="sj-col-mini" aria-label={nextLabel} />
          </tr>
        </thead>
        <tbody>
          {rows.map((row) => (
            <tr key={row.kode}>
              <td>
                <span className="sj-td-name">
                  <MapPin size={15} /> {row.name}
                </span>
              </td>
              <td className="sj-num">{nf.format(row.total)}</td>
              <td className="sj-num">{nf.format(row.sent)}</td>
              <td><BarCell value={row.progress} /></td>
              {drillable ? (
                <td>
                  <SmartLink className="sj-drill-link" href={buildPath(base, ...parentSegments, row.kode)} aria-label={`Lihat detail ${row.name}`}>
                    detail <ChevronRight size={13} />
                  </SmartLink>
                </td>
              ) : (
                <td aria-hidden="true" />
              )}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
