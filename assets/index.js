import React from 'react';
import { render } from 'react-dom'
import { BrowserRouter, Link, Route, Routes } from 'react-router-dom';
import { HomePage } from './components/pages/HomePage';
import { NotFoundPage } from './components/pages/NotFoundPage';
import { ContentWrapper, GlobalStyle } from './components/layout/Layout';
import { ManagePage } from './components/pages/ManagePage';

function App() {
    return <BrowserRouter>
        <GlobalStyle/>
        <ContentWrapper>
            <Link to="/"><h1>Super Cool Link Shortener</h1></Link>
            <Routes>
                <Route path="/"><HomePage/></Route>
                <Route path="/view/:code"><ManagePage/></Route>
                <Route path="*" element={<NotFoundPage/>}/>
            </Routes>
        </ContentWrapper>
    </BrowserRouter>
}

render(<App/>, document.getElementById('root'))