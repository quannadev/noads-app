package client

import (
	"encoding/json"
	"io"
	"net/http"
	"net/url"
)

type HttpClient struct {
	client *http.Client
}

func NewHttpClient() HttpClient {
	return HttpClient{
		client: &http.Client{},
	}
}

// NewHttpClientWithProxy Init With Proxy
func NewHttpClientWithProxy(proxy string, username, password string) *HttpClient {
	transport := &http.Transport{
		Proxy: http.ProxyURL(&url.URL{
			Host: proxy,
			User: url.UserPassword(username, password),
		}),
	}
	return &HttpClient{
		client: &http.Client{
			Transport: transport,
		},
	}
}

func (c *HttpClient) GetRaw(url string, headers *map[string]string) (*http.Response, error) {
	req, err := http.NewRequest("GET", url, nil)
	if err != nil {
		return nil, err
	}
	if headers != nil {
		for k, v := range *headers {
			req.Header.Add(k, v)
		}
	}
	return c.client.Do(req)
}

// Get Txt body
func (c *HttpClient) Get(url string, headers *map[string]string) (string, error) {
	body, err := c.GetRaw(url, headers)
	if err != nil {
		return "", err
	}
	defer body.Body.Close()

	bodyBytes, err := io.ReadAll(body.Body)
	if err != nil {
		return "", err
	}
	return string(bodyBytes), nil
}

// GetStruct Get Struct body
func (c *HttpClient) GetStruct(url string, headers *map[string]string, v interface{}) error {
	body, err := c.GetRaw(url, headers)
	if err != nil {
		return err
	}
	defer body.Body.Close()

	return json.NewDecoder(body.Body).Decode(v)
}

// ParseUrl Parse url
func (c *HttpClient) ParseUrl(uri string) (*url.URL, error) {
	return url.Parse(uri)
}

func (c *HttpClient) RemoveDuplicates(input []string) []string {
	encountered := map[string]bool{}
	var result []string

	for _, val := range input {
		if encountered[val] == false {
			encountered[val] = true
			result = append(result, val)
		}
	}

	return result
}
