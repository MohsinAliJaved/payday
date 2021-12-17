let baseUrl = '';
if (window.hasOwnProperty('tenant')) {
    baseUrl = tenant.is_single ? '' : ''
}

export const TENANT_BASE_URL = baseUrl;
