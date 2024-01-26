package providers

import "noads/providers/film"

type IProvider interface {
	CheckLink(link string) bool
	GetLink(link string) (interface{}, error)
}

type Provider struct {
	providers []IProvider
}

func NewProvider() *Provider {
	providers := []IProvider{
		film.NewMotChillUk(),
	}
	return &Provider{
		providers: providers,
	}
}

func (p *Provider) GetProvider(link string) IProvider {
	for _, provider := range p.providers {
		if provider.CheckLink(link) {
			return provider
		}
	}
	return nil
}

func (p *Provider) GetLink(link string) (interface{}, error) {
	provider := p.GetProvider(link)
	if provider == nil {
		return nil, nil
	}
	return provider.GetLink(link)
}
