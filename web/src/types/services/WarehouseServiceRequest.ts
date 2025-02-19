export interface ReadAnyRequest {
    search: string | null,
    company_id?: string,
    branch_id?: string,

    refresh: boolean,
    paginate: boolean,
    page?: number,
    per_page?: number,
}