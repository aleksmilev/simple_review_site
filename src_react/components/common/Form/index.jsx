import { Component, createRef } from 'react'
import ApiRequest from '../../../services/api'
import '../../../style/Form.css'

/**
 * @example
 * <Form 
 *   config={{ controller: 'legal', method: 'contact' }}
 *   onSuccess={(response, data) => console.log('Success!', response)}
 *   onError={(error, data) => console.error('Error!', error)}
 * >
 *   <Form.Input name="name" type="text" label="Name" required />
 *   <Form.Input name="email" type="email" label="Email" required />
 *   <Form.Select 
 *     name="subject" 
 *     label="Subject" 
 *     required
 *     placeholder="Select a subject"
 *     options={[
 *       { value: 'general', label: 'General Inquiry' },
 *       { value: 'support', label: 'Support Request' }
 *     ]}
 *   />
 *   <Form.Textarea name="message" label="Message" required rows={6} />
 *   <Form.Submit className="btn btn-primary">Send Message</Form.Submit>
 * </Form>
 */
class Form extends Component {
    constructor(props) {
        super(props)
        this.state = {
            loading: false,
            error: null,
            success: false
        }
        this.formRef = createRef()
    }

    handleSubmit = async (e) => {
        e.preventDefault()
        
        const { config, onSuccess, onError, onSubmit, beforeSend } = this.props
        
        if (onSubmit) {
            const result = await onSubmit(e)
            if (result === false) {
                return
            }
        }

        const formData = new FormData(e.target)
        let data = {}
        
        for (const [key, value] of formData.entries()) {
            data[key] = value
        }

        if (beforeSend) {
            data = await beforeSend(data)
        }

        this.setState({ loading: true, error: null, success: false })

        try {
            const url = `/${config.controller}/${config.method}`
            const request = new ApiRequest({
                url: url,
                method: 'POST',
                params: data
            })

            const response = await request.exec()

            if (response.status === 'OK') {
                this.setState({ loading: false, success: true, error: null })
                
                if (onSuccess) {
                    onSuccess(response.response, data)
                }

                if (this.props.resetOnSuccess !== false) {
                    e.target.reset()
                }
            } else {
                const errorMessage = response.response?.error || 'An error occurred'
                this.setState({ loading: false, error: errorMessage, success: false })
                
                if (onError) {
                    onError(response.response, data)
                }
            }
        } catch (error) {
            const errorMessage = error.message || 'An unexpected error occurred'
            this.setState({ loading: false, error: errorMessage, success: false })
            
            if (onError) {
                onError({ error: errorMessage }, data)
            }
        }
    }

    render() {
        const { 
            children, 
            className = '', 
            formClassName,
            showSuccessMessage = true,
            showErrorMessage = true,
            successMessage = 'Form submitted successfully!',
            loadingText = 'Submitting...'
        } = this.props
        const { loading, error, success } = this.state

        const formClass = formClassName || 'common-form'

        return (
            <form 
                ref={this.formRef}
                className={`${formClass} ${className}`.trim()}
                onSubmit={this.handleSubmit}
            >
                {children}
            </form>
        )
    }
}

class FormInput extends Component {
    render() {
        const { 
            name, 
            type = 'text', 
            label, 
            id, 
            required = false,
            placeholder,
            value,
            onChange,
            className = '',
            ...props 
        } = this.props
        
        const inputId = id || name
        
        return (
            <div className={`form-group ${className}`}>
                {label && (
                    <label htmlFor={inputId}>
                        {label}
                        {required && <span className="required">*</span>}
                    </label>
                )}
                <input
                    type={type}
                    id={inputId}
                    name={name}
                    required={required}
                    placeholder={placeholder}
                    value={value}
                    onChange={onChange}
                    {...props}
                />
            </div>
        )
    }
}

class FormSelect extends Component {
    render() {
        const { 
            name, 
            label, 
            id, 
            required = false,
            options = [],
            value,
            onChange,
            className = '',
            placeholder,
            ...props 
        } = this.props
        
        const inputId = id || name
        
        return (
            <div className={`form-group ${className}`}>
                {label && (
                    <label htmlFor={inputId}>
                        {label}
                        {required && <span className="required">*</span>}
                    </label>
                )}
                <select
                    id={inputId}
                    name={name}
                    required={required}
                    value={value}
                    onChange={onChange}
                    {...props}
                >
                    {placeholder && (
                        <option value="" disabled hidden>
                            {placeholder}
                        </option>
                    )}
                    {options.map((option) => {
                        if (typeof option === 'string') {
                            return (
                                <option key={option} value={option}>
                                    {option}
                                </option>
                            )
                        }
                        return (
                            <option key={option.value} value={option.value}>
                                {option.label || option.value}
                            </option>
                        )
                    })}
                </select>
            </div>
        )
    }
}

class FormTextarea extends Component {
    render() {
        const { 
            name, 
            label, 
            id, 
            required = false,
            placeholder,
            value,
            onChange,
            className = '',
            rows = 6,
            ...props 
        } = this.props
        
        const inputId = id || name
        
        return (
            <div className={`form-group ${className}`}>
                {label && (
                    <label htmlFor={inputId}>
                        {label}
                        {required && <span className="required">*</span>}
                    </label>
                )}
                <textarea
                    id={inputId}
                    name={name}
                    required={required}
                    placeholder={placeholder}
                    value={value}
                    onChange={onChange}
                    rows={rows}
                    {...props}
                />
            </div>
        )
    }
}

class FormSubmit extends Component {
    render() {
        const { 
            children, 
            className = 'btn btn-primary',
            disabled,
            ...props 
        } = this.props
        
        return (
            <button 
                type="submit" 
                className={className}
                disabled={disabled}
                {...props}
            >
                {children || 'Submit'}
            </button>
        )
    }
}

Form.Input = FormInput
Form.Select = FormSelect
Form.Textarea = FormTextarea
Form.Submit = FormSubmit

export default Form

