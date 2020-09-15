import React from 'react'
import styled from 'styled-components'

const ErrorContainer = styled.div`
  color: #f14668;
`

export const Errors = ({title, children}) => <ErrorContainer>
    {title && <h3>{title}</h3>}
    {children}
</ErrorContainer>
