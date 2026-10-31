import type { School } from "../types";

/** Twelve sample schools for the demo catalogue (NOT official data). */
export const schools: School[] = [
  { npsn: "20100188", name: "SD Negeri Menteng 01", level: "SD", status: "Negeri", city: "Jakarta Pusat", province: "DKI Jakarta", district: "Menteng", address: "Jl. Diponegoro No. 34, Menteng", established: "1918", accreditation: "A", students: 1320, teachers: 54, rts: 24 },
  { npsn: "20104112", name: "SDN 01 Cilandak", level: "SD", status: "Negeri", city: "Jakarta Selatan", province: "DKI Jakarta", district: "Cilandak", address: "Jl. Cilandak KKO No. 7", established: "1982", accreditation: "B", students: 980, teachers: 41, rts: 18 },
  { npsn: "69800001", name: "TK Harapan Bangsa", level: "PAUD", status: "Swasta", city: "Jakarta Selatan", province: "DKI Jakarta", district: "Pancoran", address: "Jl. Jend. Gatot Subroto No. 21", established: "2004", accreditation: "B", students: 156, teachers: 14, rts: 6 },
  { npsn: "20219258", name: "SMP Negeri 1 Bandung", level: "SMP", status: "Negeri", city: "Kota Bandung", province: "Jawa Barat", district: "Coblong", address: "Jl. Ciliwung No. 26, Coblong", established: "1959", accreditation: "A", students: 1512, teachers: 82, rts: 30 },
  { npsn: "20200001", name: "SD Negeri 1 Bandung", level: "SD", status: "Negeri", city: "Kota Bandung", province: "Jawa Barat", district: "Sumur Bandung", address: "Jl. Merdeka No. 12", established: "1926", accreditation: "A", students: 1188, teachers: 49, rts: 22 },
  { npsn: "20241703", name: "SMK Negeri 2 Bandung", level: "SMK", status: "Negeri", city: "Kota Bandung", province: "Jawa Barat", district: "Cibeunying Kaler", address: "Jl. PHH. Mustang No. 8", established: "1967", accreditation: "A", students: 2016, teachers: 108, rts: 38 },
  { npsn: "20403174", name: "SMA Negeri 1 Yogyakarta", level: "SMA", status: "Negeri", city: "Kota Yogyakarta", province: "DI Yogyakarta", district: "Jetis", address: "Jl. Suroto No. 2, Kotabaru", established: "1951", accreditation: "A", students: 1350, teachers: 86, rts: 27 },
  { npsn: "20406100", name: "SLB Negeri Pembina Yogyakarta", level: "SLB", status: "Negeri", city: "Kota Yogyakarta", province: "DI Yogyakarta", district: "Sleman", address: "Jl. Kaliwarman No. 5", established: "1982", accreditation: "B", students: 310, teachers: 48, rts: 12 },
  { npsn: "20532164", name: "SMK Negeri 1 Surabaya", level: "SMK", status: "Negeri", city: "Kota Surabaya", province: "Jawa Timur", district: "Genteng", address: "Jl. Raya Gubeng No. 82", established: "1961", accreditation: "A", students: 2140, teachers: 115, rts: 40 },
  { npsn: "20502034", name: "SMA Negeri 5 Surabaya", level: "SMA", status: "Negeri", city: "Kota Surabaya", province: "Jawa Timur", district: "Mulyorejo", address: "Jl. Raya Mulyosari No. 2", established: "1988", accreditation: "A", students: 1260, teachers: 79, rts: 26 },
  { npsn: "10505602", name: "SD Negeri Inpres 14 Kupang", level: "SD", status: "Negeri", city: "Kota Kupang", province: "Nusa Tenggara Timur", district: "Oebobo", address: "Jl. Tituni Raya No. 4", established: "1974", accreditation: "B", students: 754, teachers: 33, rts: 15 },
  { npsn: "60721411", name: "SMK Negeri 1 Denpasar", level: "SMK", status: "Negeri", city: "Kota Denpasar", province: "Bali", district: "Dentim", address: "Jl. Surapati No. 19", established: "1970", accreditation: "A", students: 1890, teachers: 96, rts: 34 },
];

export function findSchoolByNpsn(npsn: string | undefined): School | undefined {
  return npsn ? schools.find((school) => school.npsn === npsn) : undefined;
}
