import axios from "../axios";
import { useZiggyRouteStore } from "../stores/ziggy-route";
import { route, Config } from "ziggy-js";
import { Branch } from "../types/models/Branch";
import { Resource } from "../types/resources/Resource";
import { Collection } from "../types/resources/Collection";
import { ServiceResponse } from "../types/services/ServiceResponse";
import { AxiosError, AxiosResponse, isAxiosError } from "axios";
import ErrorHandlerService from "./ErrorHandlerService";
import { ReadAnyRequest } from "../types/services/ServiceRequest";
import { StatusCode } from "../types/enums/StatusCode";
import { client, useForm } from "laravel-precognition-vue";

export default class BranchService {
    private ziggyRoute: Config;
    private ziggyRouteStore = useZiggyRouteStore();

    private errorHandlerService;

    constructor() {
        this.ziggyRoute = this.ziggyRouteStore.getZiggy;

        this.errorHandlerService = new ErrorHandlerService();
    }

    public useBranchCreateForm() {
        const url = route('api.post.db.company.branch.save', undefined, true, this.ziggyRoute);

        client.axios().defaults.withCredentials = true;
        client.axios().defaults.withXSRFToken = true;
        const form = useForm('post', url, {
            company_id: '',
            code: '_AUTO_',
            name: '',
            address: '',
            city: '',
            contact: '',
            is_main: false,
            remarks: '',
            status: 'ACTIVE',
        });

        return form;
    }

    public async readAny(args: ReadAnyRequest): Promise<ServiceResponse<Collection<Array<Branch>> | Resource<Array<Branch>> | null>> {
        const result: ServiceResponse<Collection<Array<Branch>> | Resource<Array<Branch>> | null> = {
            success: false
        }

        try {
            const queryParams: Record<string, string | number | boolean> = {};
            if (args.company_id) {
                queryParams['company_id'] = args.company_id;
            }
            queryParams['search'] = args.search ? args.search : '';
            queryParams['refresh'] = args.refresh;
            queryParams['paginate'] = args.paginate;
            if (args.page) queryParams['page'] = args.page;
            if (args.per_page) queryParams['per_page'] = args.per_page;

            const url = route('api.get.db.company.branch.read_any', {
                _query: queryParams
            }, false, this.ziggyRoute);

            const response: AxiosResponse<Collection<Array<Branch>>> = await axios.get(url);

            if (response.status == StatusCode.OK) {
                result.success = true;
                result.data = response.data;
            }

            return result;
        } catch (e: unknown) {
            if (e instanceof Error && e.message.includes('Ziggy error')) {
                return this.errorHandlerService.generateZiggyUrlErrorServiceResponse(e.message);
            } else if (isAxiosError(e)) {
                return this.errorHandlerService.generateAxiosErrorServiceResponse(e as AxiosError);
            } else {
                return result;
            }
        }
    }

    public async read(ulid: string): Promise<ServiceResponse<Branch | null>> {
        const result: ServiceResponse<Branch | null> = {
            success: false
        }

        try {
            const url = route('api.get.db.company.branch.read', {
                branch: ulid
            }, false, this.ziggyRoute);

            const response: AxiosResponse<Resource<Branch>> = await axios.get(url);

            if (response.status == StatusCode.OK) {
                result.success = true;
                result.data = response.data.data;
            }

            return result;
        } catch (e: unknown) {
            if (e instanceof Error && e.message.includes('Ziggy error')) {
                return this.errorHandlerService.generateZiggyUrlErrorServiceResponse(e.message);
            } else if (isAxiosError(e)) {
                return this.errorHandlerService.generateAxiosErrorServiceResponse(e as AxiosError);
            } else {
                return result;
            }
        }
    }

    public useBranchEditForm(ulid: string) {
        const url = route('api.post.db.company.branch.edit', ulid, true, this.ziggyRoute);

        client.axios().defaults.withCredentials = true;
        client.axios().defaults.withXSRFToken = true;
        const form = useForm('post', url, {
            company_id: '',
            code: '_AUTO_',
            name: '',
            address: '',
            city: '',
            contact: '',
            is_main: false,
            remarks: '',
            status: 'ACTIVE',
        });

        return form;
    }

    public async delete(ulid: string): Promise<ServiceResponse<boolean | null>> {
        const result: ServiceResponse<boolean | null> = {
            success: false,
        }

        try {
            const url = route('api.post.db.company.branch.delete', ulid, false, this.ziggyRoute);

            const response: AxiosResponse<boolean | null> = await axios.post(url);

            if (response.status == StatusCode.OK) {
                result.success = true;
            }

            return result;
        } catch (e: unknown) {
            if (e instanceof Error && e.message.includes('Ziggy error')) {
                return this.errorHandlerService.generateZiggyUrlErrorServiceResponse(e.message);
            } else if (isAxiosError(e)) {
                return this.errorHandlerService.generateAxiosErrorServiceResponse(e as AxiosError);
            } else {
                return result;
            }
        }
    }
}