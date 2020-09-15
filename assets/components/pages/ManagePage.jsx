import { SelectInput } from '../SelectInput'
import { useLocation, useNavigate, useParams } from 'react-router-dom'
import React, { useEffect, useState } from 'react'
import qs from 'qs'
import axios from 'axios'
import { Errors } from '../Error';

const buildUrl = code => `${window.location.origin}/${code}`

export function ManagePage() {
    const location = useLocation()
    const [linkData, setLinkData] = useState(location.state)
    const [error, setError] = useState(undefined)
    const { code } = useParams()
    const { secret } = qs.parse(location.search, { ignoreQueryPrefix: true })
    const navigate = useNavigate();

    useEffect(() => {
        (async () => {
            try {
                const response = await axios.get(`/api/link/${code}?${qs.stringify({ secret })}`)
                console.log(response);
                setLinkData(response.data)
            } catch (e) {
                setError('There was a problem fetching data about this link')
            }
        })()
    }, [code])

    const deleteLink = async () => {
        try {
            await axios.delete(`/api/link/${code}?${qs.stringify({ secret })}`)
            navigate('/')
        } catch (e) {
            setError('There was a problem deleting your link')
        }
    }

    return <>
        {error && <Errors title={error}/>}
        {linkData && <div>
            {linkData.code && <>
                <label>Short link:</label>
                <SelectInput value={buildUrl(linkData.code)}/>
                <br/><br/>
                <label>Redirects to:</label><br/>
                <a href={linkData.url} target="_blank" rel="noreferrer">{linkData.url}</a>
                {linkData.hasOwnProperty('visits') && <>
                    <br/><br/>
                    <label>Has been visited: {linkData.visits} times</label>
                </>}
                {secret && <>
                    <br/><br/>
                    <button
                        style={{ backgroundColor: '#f14668', color: '#fff' }}
                        onClick={deleteLink}
                    >
                        Delete Link
                    </button>
                </>}
            </>}
        </div>}
    </>
}