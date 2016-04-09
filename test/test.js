'use strict';

const supertest = require('supertest');
const should    = require('should');

const baseuri = 'https://web.njit.edu/~mjc55/CS490/';
const agent   = supertest.agent(baseuri);

describe('Login', () => {
	
	it('should login', (done) => {
		var userInfo = {
			username: 'alice',
			password: 'password'
		};
		
		agent.post('user/login.php')
			.send(userInfo)
			.expect(200)
			.expect('Content-Type', 'application/json; charset=UTF-8')
			.expect((response) => {
				response.should.have.property('body');
				response.body.should.have.property('sessionID');
				response.body.sessionID.should.be.type('string');
		}).end(done);
	
	});
});
