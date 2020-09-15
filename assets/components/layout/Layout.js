import React from 'react'
import styled, { createGlobalStyle } from 'styled-components'

export const GlobalStyle = createGlobalStyle`
  body, html {
    margin: 0;
    padding: 0;
    font-family: 'Roboto', sans-serif;
    color: #666;
  }
  
  * {
    box-sizing: border-box;
  }

  #root {
    background: linear-gradient(136.48deg, #fcbc00 8.9%, #30c8fb 101.04%);
    position: static;
    width: 100vw;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  h1 {
    color: #555;
    font-size: 220%;
    margin-top: 0;
  }
  
  a {
    text-decoration: none;
    color: #0168d6
  },
  
  label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
  }
  
  input, textarea {
    border: none;
    padding: 14px;
    background: #EFEFEF;
    border-radius: 5px;
    width: 100%;
      
    &::placeholder {
      color: #B6B6B6;
    }
  }
  
  button {
    border: none;
    padding: 11px 22px;
    border-radius: 5px;
    cursor: pointer;
    &:disabled {
      opacity: 0.8;
      cursor: initial;
    }
  }
`

export const ContentWrapper = styled.div`
  background: rgba(255, 255, 255, 0.98);
  border-radius: 10px;
  box-shadow: 3px 3px 5px 0 rgba(0,0,0,0.3);
  padding: 50px;
`;
