import { ChevronRight, School as SchoolIcon } from "lucide-react";
import type { School } from "../../types";
import { buildPath } from "../../config/routes";
import { SmartLink } from "../ui/SmartLink";

/** Search result row linking to /sekolah/?npsn=… (validated 8-digit NPSN). */
export function SchoolResultCard({ school }: { school: School }) {
  return (
    <SmartLink className="sj-school-result" href={`${buildPath("/sekolah")}?npsn=${encodeURIComponent(school.npsn)}`}>
      <span className="sj-result-icon">
        <SchoolIcon size={17} />
      </span>
      <span>
        <strong>{school.name}</strong>
        <small>
          NPSN {school.npsn} · {school.city}
        </small>
      </span>
      <ChevronRight size={18} />
    </SmartLink>
  );
}
