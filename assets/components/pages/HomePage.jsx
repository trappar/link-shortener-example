import React, { useState } from 'react'
import styled from 'styled-components'
import { Form, Formik } from 'formik'
import * as yup from 'yup'
import { FieldContainer, Input } from '../Forms'
import axios from 'axios'
import { useNavigate } from 'react-router-dom';
import { Errors } from '../Error';

const validationSchema = yup.object({
    url: yup.string()
        .required('Must not be blank')
        .url('Must be a valid url'),
    desiredCode: yup.string()
        .matches(/^[a-zA-Z0-9- ]+$/, 'Must contain only letters, numbers, and dashes (-)')
        .min(5, 'Must be at least 5 characters')
        .max(30, 'Must be 30 or fewer characters long')
})

const initialValues = {
    url: '',
    desiredCode: ''
}

const SubmitButton = styled.button`
  background-color: #28a7d2;
  color: #fff;
`

export function HomePage() {
    const [errors, setErrors] = useState([]);
    const navigate = useNavigate();

    const submit = async values => {
        try {
            const response = await axios.post('/api/link', values);
            if (response.data.errors) {
                setErrors(response.data.errors)
            } else {
                navigate(`/view/${response.data.code}?secret=${response.data.secret}`, { state: response.data })
            }
        } catch (e) {
            setErrors([{ path: '', error: 'There was an unknown communication issue.' }]);
        }
    }

    return <>
        <Formik
            initialValues={initialValues}
            validationSchema={validationSchema}
            onSubmit={submit}
        >
            {({ isSubmitting, isValid }) => <Form>
                <FieldContainer name="url" label="URL">
                    <Input placeholder="https://some-cool-website.com"/>
                </FieldContainer>
                <FieldContainer name="desiredCode" label="Desired Code">
                    <Input placeholder="Leave blank to have one auto-generated"/>
                </FieldContainer>
                <SubmitButton type="submit" disabled={isSubmitting || !isValid}>
                    {isSubmitting ? 'Submitting' : 'Shorten'}
                </SubmitButton>
            </Form>}
        </Formik>
        {errors.length > 0 && <>
            <br/>
            <Errors title="There were issues creating your short link">
                <ul>{errors.map(({ path, error }, i) => <li key={i}>{path} - {error}</li>)}</ul>
            </Errors>
        </>}
    </>
}