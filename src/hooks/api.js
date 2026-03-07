class ApiConfig {
    static baseUrl = 'http://localhost:8008/api/'

    static schematic = {
        'legal': {
            'contact': [
                {
                    'method': 'POST',
                    'params': ['name', 'email', 'subject', 'message']
                },
                {
                    'method': 'GET',
                    'params': []
                }
            ],
        },
        'user': {
            'login': [
                {
                    'method': 'POST',
                    'params': ['email', 'password'],
                },
            ],
            'register': [
                {
                    'method': 'POST',
                    'params': ['username', 'email', 'password']
                }
            ],
            'profile': [
                {
                    'method': 'GET',
                    'params': ['token']
                }
            ]
        }
    }

    static buildConfig(request) {
        const urlPath = request.url.startsWith('/') ? request.url.slice(1) : request.url;
        const pathParts = urlPath.split('/');
        
        if (pathParts.length < 2) {
            throw new Error('Invalid URL format. Expected format: /category/endpoint');
        }

        const [category, endpoint] = pathParts;
        
        if (!ApiConfig.schematic[category]) {
            throw new Error(`Category '${category}' not found in schematic`);
        }

        if (!ApiConfig.schematic[category][endpoint]) {
            throw new Error(`Endpoint '${endpoint}' not found in category '${category}'`);
        }

        const methodConfigs = ApiConfig.schematic[category][endpoint];
        const methodConfig = methodConfigs.find(config => config.method === request.method);

        if (!methodConfig) {
            throw new Error(`Method '${request.method}' not allowed for ${category}/${endpoint}`);
        }

        const expectedParams = methodConfig.params || [];
        const providedParams = request.params || {};
        
        for (const param of expectedParams) {
            if (!(param in providedParams)) {
                throw new Error(`Missing required parameter: ${param}`);
            }
        }

        const fullUrl = `${ApiConfig.baseUrl}${urlPath}`;

        return {
            url: fullUrl,
            method: request.method,
            params: providedParams,
            expectedParams: expectedParams
        };
    }
}

class ApiRequest {
    config = null;

    constructor(config) {
        if (!config || !config.url || !config.method) {
            throw new Error('Invalid config. Required: {url, method, params}');
        }
        this.config = config;
    }

    handleError(error) {
        return {
            status: 'ERROR',
            response: {
                errorType: error.type || 'unknown_error',
                error: error.message || 'unknown_error',
            }
        };
    }

    handleSuccess(responseData) {
        return {
            status: 'OK',
            response: responseData
        };
    }

    handleRefusedAccess() {
        return {
            status: 'ERROR',
            response: {
                errorType: 'permission_denied',
                error: 'Permission denied',
            }
        };
    }

    async exec() {
        try {
            const validatedConfig = ApiConfig.buildConfig(this.config);
            
            const requestOptions = {
                method: validatedConfig.method,
                headers: {
                    'Content-Type': 'application/json',
                }
            };

            if (['POST', 'PUT', 'PATCH'].includes(validatedConfig.method)) {
                requestOptions.body = JSON.stringify(validatedConfig.params);
            } else if (validatedConfig.method === 'GET') {
                const queryParams = new URLSearchParams(validatedConfig.params).toString();
                validatedConfig.url = queryParams 
                    ? `${validatedConfig.url}?${queryParams}`
                    : validatedConfig.url;
            }

            const response = await fetch(validatedConfig.url, requestOptions);
            
            if (response.status === 401 || response.status === 403) {
                return this.handleRefusedAccess();
            }

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ error: 'request_failed' }));
                return this.handleError(new Error(errorData.error || `HTTP ${response.status}`));
            }

            const responseData = await response.json().catch(() => ({}));
            return this.handleSuccess(responseData);

        } catch (error) {
            return this.handleError(error);
        }
    }
}

export default ApiRequest
