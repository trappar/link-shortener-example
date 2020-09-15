import React, { useContext, useMemo } from 'react'
import { useField as useBaseField } from 'formik'
import styled from 'styled-components'

const FieldContext = React.createContext({})

function useField(nameOrProps) {
    const { name, id } = useContext(FieldContext)
    const resolvedName = name || nameOrProps
    if (!resolvedName && !resolvedName.name) {
        throw new Error('Name not found in useField')
    }
    const [field, meta, helper] = useBaseField(resolvedName)

    return {
        field: { ...field, id },
        meta,
        helper,
        status: {
            isTouched: meta.touched,
            isValid: meta.touched && !meta.error,
            isErrored: meta.touched && meta.error,
        },
    }
}

const Field = styled.div`
  margin-bottom: 20px;
`;

const Errors = styled.div`
  font-size: 12px;
  color: #f14668;
`

let fieldId = 0

export function FieldContainer(
    {
        name,
        label,
        className,
        children,
    },
) {
    const [, meta] = useBaseField(name)

    const id = useMemo(
        () => `${name}_${fieldId++}`,
        [name],
    )

    return <Field className={className}>
        {label && <label htmlFor={id}>{/* eslint-disable-line jsx-a11y/label-has-for */}
            {label}
        </label>}
        <FieldContext.Provider value={{ name, id }}>
            {children}
        </FieldContext.Provider>
        {meta.touched && meta.error && (
            <Errors>{meta.error}</Errors>
        )}
    </Field>
}

export function Input(props) {
    const { field } = useField(props)
    return <input type="text" {...field} {...props}/>
}