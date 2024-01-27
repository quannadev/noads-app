package cache

import (
	"fmt"
	"time"
)

type cacheItem struct {
	timestamp int64
	data      []byte
}

type Memory struct {
	data map[string]cacheItem
}

func NewMemory() *Memory {
	m := &Memory{
		data: make(map[string]cacheItem, 0),
	}
	go m.checkExpire()
	return m
}

func (m *Memory) Get(key string) (interface{}, error) {
	key = HashKey(key)
	item, ok := m.data[key]
	if !ok {
		return nil, fmt.Errorf("key %s not found", key)
	}
	data := interface{}(nil)
	err := DecodeToByte(item.data, data)
	if err != nil {
		return nil, err
	}
	return data, nil
}

func (m *Memory) Set(key string, value interface{}) error {
	key = HashKey(key)
	data, err := EncodeToByte(value)
	if err != nil {
		return err
	}
	item := cacheItem{
		timestamp: time.Now().Unix(),
		data:      data,
	}
	m.data[key] = item

	//limit 1000 items
	if len(m.data) > 1000 {
		for k := range m.data {
			delete(m.data, k)
			break
		}
	}
	return nil
}

func (m *Memory) Delete(key string) error {
	key = HashKey(key)
	delete(m.data, key)
	return nil
}

func (m *Memory) checkExpire() {
	maxTime := float64(10 * time.Minute)
	for {
		if len(m.data) == 0 {
			time.Sleep(10 * time.Minute)
			continue
		}
		timeNow := time.Now().Unix()
		for k, v := range m.data {
			if time.Duration(timeNow-v.timestamp).Minutes() > maxTime {
				delete(m.data, k)
			}
		}
		time.Sleep(1 * time.Minute)
	}
}
